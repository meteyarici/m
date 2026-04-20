package main

import (
	"context"
	"encoding/json"
	"log"
	"net/http"
	"os"
	"strings"
	"time"

	"github.com/gorilla/websocket"
	"github.com/redis/go-redis/v9"
)

// =====================
// CONFIG
// =====================
const (
	readWait       = 10 * time.Second
	writeWait      = 10 * time.Second
	pingPeriod     = 5 * time.Second
	maxMessageSize = 512
)

// =====================
// REDIS
// =====================
func getRedisAddr() string {
	if host := os.Getenv("REDIS_HOST"); host != "" {
		return host + ":6379"
	}
	return "localhost:6379"
}

var rdb = redis.NewClient(&redis.Options{
	Addr: getRedisAddr(),
})

// =====================
// WS UPGRADER
// =====================
var upgrader = websocket.Upgrader{
	ReadBufferSize:  1024,
	WriteBufferSize: 1024,
	CheckOrigin: func(r *http.Request) bool {
		return true
	},
}

// =====================
// CLIENT
// =====================
type Client struct {
	hub    *Hub
	conn   *websocket.Conn
	userID int64
	roomID string
	send   chan []byte
}

func (c *Client) readPump() {
	defer func() {
		c.hub.unregister <- c
		c.conn.Close()
	}()

	c.conn.SetReadLimit(maxMessageSize)
	c.conn.SetReadDeadline(time.Now().Add(readWait))
	c.conn.SetPongHandler(func(string) error {
		c.conn.SetReadDeadline(time.Now().Add(readWait))
		return nil
	})

	for {
		_, message, err := c.conn.ReadMessage()
		if err != nil {
			break
		}

		c.hub.broadcast <- RoomMessage{
			roomID: c.roomID,
			data:   message,
		}
	}
}

func (c *Client) writePump() {
	ticker := time.NewTicker(pingPeriod)
	defer func() {
		ticker.Stop()
		c.conn.Close()
	}()

	for {
		select {
		case message, ok := <-c.send:
			c.conn.SetWriteDeadline(time.Now().Add(writeWait))
			if !ok {
				c.conn.WriteMessage(websocket.CloseMessage, []byte{})
				return
			}
			if err := c.conn.WriteMessage(websocket.TextMessage, message); err != nil {
				return
			}

		case <-ticker.C:
			c.conn.SetWriteDeadline(time.Now().Add(writeWait))
			if err := c.conn.WriteMessage(websocket.PingMessage, nil); err != nil {
				return
			}
		}
	}
}

// =====================
// HUB + ROOM
// =====================
type RoomMessage struct {
	roomID string
	data   []byte
}

type Hub struct {
	rooms      map[string]map[*Client]bool
	broadcast  chan RoomMessage
	register   chan *Client
	unregister chan *Client
}

func (h *Hub) run() {
	for {
		select {

		case c := <-h.register:
			if h.rooms[c.roomID] == nil {
				h.rooms[c.roomID] = make(map[*Client]bool)
			}
			h.rooms[c.roomID][c] = true
			log.Printf("User %d joined room %s (clients=%d)",
				c.userID, c.roomID, len(h.rooms[c.roomID]))

			welcome := map[string]interface{}{
				"type":    "system",
				"message": "Hoşgeldin kullanıcı",
				"user_id": c.userID,
				"room_id": c.roomID,
			}

			payload, _ := json.Marshal(welcome)
			c.send <- payload

		case c := <-h.unregister:
			if room, ok := h.rooms[c.roomID]; ok {
				if _, exists := room[c]; exists {
					delete(room, c)
					close(c.send)
				}
				if len(room) == 0 {
					delete(h.rooms, c.roomID)
					log.Printf("Room %s destroyed", c.roomID)
				}
			}

		case msg := <-h.broadcast:
			if room, ok := h.rooms[msg.roomID]; ok {
				for c := range room {
					select {
					case c.send <- msg.data:
					default:
						close(c.send)
						delete(room, c)
					}
				}
			}
		}
	}
}

// =====================
// AUTH (OPAQUE TOKEN)
// =====================
func validateOpaqueToken(ctx context.Context, token string) (int64, string, error) {
	key := "opaque:token:" + token

	val, err := rdb.Get(ctx, key).Result()
	if err != nil {
		return 0, "", err
	}

	var data struct {
		UserID int64  `json:"user_id"`
		RoomID string `json:"room_id"`
	}

	if err := json.Unmarshal([]byte(val), &data); err != nil {
		return 0, "", err
	}

	// single-use token
	//burası önemli
	// rdb.Del(ctx, key)

	return data.UserID, data.RoomID, nil
}

// =====================
// WS HANDLER
// =====================
func serveWs(hub *Hub, w http.ResponseWriter, r *http.Request) {
	token := r.URL.Query().Get("token")
	if token == "" {
		http.Error(w, "missing token", http.StatusUnauthorized)
		return
	}

	userID, roomID, err := validateOpaqueToken(r.Context(), token)
	if err != nil {
		http.Error(w, "invalid token", http.StatusUnauthorized)
		return
	}

	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		return
	}

	client := &Client{
		hub:    hub,
		conn:   conn,
		userID: userID,
		roomID: roomID,
		send:   make(chan []byte, 256),
	}

	hub.register <- client

	go client.writePump()
	go client.readPump()
}

// =====================
// REDIS -> HUB BRIDGE
// =====================
//
// Laravel'in BidService'i Redis'e şu kanala publish eder:
//   PUBLISH auction:{id}:bids <json>
// Bu goroutine tüm bu kanallara PSUBSCRIBE olur ve mesajı
// eşleşen room'a broadcast eder. Room adı: "auction:{id}".
func runRedisBridge(ctx context.Context, hub *Hub) {
	pattern := "auction:*:bids"
	pubsub := rdb.PSubscribe(ctx, pattern)
	defer pubsub.Close()

	if _, err := pubsub.Receive(ctx); err != nil {
		log.Printf("redis bridge subscribe failed: %v", err)
		return
	}

	log.Printf("📡 Redis bridge listening on %s", pattern)

	ch := pubsub.Channel()
	for msg := range ch {
		// msg.Channel = "auction:123:bids" -> roomID = "auction:123"
		roomID := msg.Channel
		if idx := strings.LastIndex(roomID, ":bids"); idx > 0 {
			roomID = roomID[:idx]
		}

		hub.broadcast <- RoomMessage{
			roomID: roomID,
			data:   []byte(msg.Payload),
		}
	}
}

// =====================
// MAIN
// =====================
func main() {
	hub := &Hub{
		rooms:      make(map[string]map[*Client]bool),
		broadcast:  make(chan RoomMessage),
		register:   make(chan *Client),
		unregister: make(chan *Client),
	}

	go hub.run()

	go runRedisBridge(context.Background(), hub)

	http.HandleFunc("/ws", func(w http.ResponseWriter, r *http.Request) {
		serveWs(hub, w, r)
	})

	log.Println("🚀 WebSocket server listening on :8081")
	log.Fatal(http.ListenAndServe(":8081", nil))
}
