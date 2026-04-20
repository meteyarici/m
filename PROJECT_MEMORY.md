# Project Memory — m365 (Bagisto E-Commerce)

## 🏗️ Teknoloji Yığını

| Katman | Teknoloji |
|---|---|
| Backend | Laravel 11, PHP 8.2+ |
| E-Commerce | Bagisto (modüler, `packages/Webkul/*`) |
| Sunucu | Laravel Octane + Swoole |
| Veritabanı | MySQL 8.0 |
| Cache / Queue / Session | Redis |
| Arama Motoru | Elasticsearch 7.17 |
| WebSocket | Go (Gorilla WebSocket) + Redis opaque token auth |
| Frontend | Inertia.js, Vite, Tailwind CSS v4 |
| Gerçek Zamanlı | Laravel Reverb |
| Konteyner | Docker Compose (Sail tabanlı) |

---

## 🐳 Docker Servisleri

| Servis | Port | Açıklama |
|---|---|---|
| `laravel.test` | 80, 443 | Ana uygulama (Octane/Swoole) |
| `mysql` | 3306 | Veritabanı |
| `redis` | 6379 | Cache, Session, Queue |
| `elasticsearch` | 9200, 9300 | Ürün arama motoru |
| `kibana` | 5601 | Elasticsearch yönetim paneli |
| `websocket` | 8081 | Go WebSocket sunucusu |
| `mailpit` | 1025, 8025 | Geliştirme mail sunucusu |

---

## 🚀 Sık Kullanılan Komutlar

### Docker
```bash
# Tüm servisleri başlat
docker compose up -d

# Tüm servisleri durdur
docker compose down

# Sadece WebSocket sunucusunu yeniden başlat
docker compose restart websocket

# WebSocket sunucusunu yeniden build et
docker compose build websocket

# Container loglarını izle
docker logs -f go-websocket
```

### Laravel (Sail üzerinden)
```bash
# Artisan komutları
./vendor/bin/sail artisan <komut>

# Elasticsearch indekslerini oluştur/güncelle
./vendor/bin/sail artisan indexer:index --type=elastic --mode=full

# Tüm indeksleri güncelle (fiyat, envanter, flat, elastic)
./vendor/bin/sail artisan indexer:index --mode=full

# Cache temizle
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear

# Migration
./vendor/bin/sail artisan migrate

# Queue worker
./vendor/bin/sail artisan queue:work
```

---

## 📁 Proje Yapısı (Önemli Dizinler)

```
my-bagisto-store/
├── packages/Webkul/         # Bagisto modülleri (Admin, Shop, Product, vb.)
├── docker-compose.yml       # Docker yapılandırması
├── docker/swoole/           # Swoole Dockerfile
├── config/elasticsearch.php # Elasticsearch ayarları
└── .env                     # Ortam değişkenleri

websocket-server/            # Go WebSocket sunucusu (ayrı repo)
├── server.go                # Ana sunucu kodu (Hub/Room/Client mimarisi)
├── Dockerfile               # Multi-stage Go build
├── go.mod / go.sum          # Go bağımlılıkları
└── k6/                      # Yük testi scriptleri
```

---

## 🔧 Bilinen Sorunlar & Çözümler

### Elasticsearch "index_not_found" Hatası
**Sorun:** `products_default_tr_index` bulunamıyor (404).
**Çözüm:**
```bash
./vendor/bin/sail artisan indexer:index --type=elastic --mode=full
```

### Saat Dilimi
`.env` dosyasında `APP_TIMEZONE=Asia/Kolkata` olarak kalmış. Türkiye için `Europe/Istanbul` olarak değiştirilmeli.

---

## 📝 Yapılan Değişiklikler Geçmişi

### 21 Mart 2026
- **Elasticsearch indeksleme:** `products_default_tr_index` oluşturuldu (`indexer:index --type=elastic --mode=full`)
- **WebSocket Docker entegrasyonu:**
  - `websocket-server/Dockerfile` oluşturuldu (multi-stage Alpine build)
  - `server.go` — Redis adresi `REDIS_HOST` env var'dan okunacak şekilde güncellendi
  - `go.mod` — Go sürümü düzeltildi (1.25.5 → 1.23)
  - `docker-compose.yml` — `websocket` servisi eklendi (port 8081, Redis bağlantılı)
- **Proje temizliği:** Eski `socket` klasörü silindi, `sockket2` → `websocket-server` olarak yeniden adlandırıldı
