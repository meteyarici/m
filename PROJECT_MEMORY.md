# Project Memory — m365 (Bagisto E-Commerce)

> **Workspace:** `/home/meteyar/projects/m`
> **Proje Tipi:** Bagisto tabanlı Laravel E-Commerce (modüler monolit) + Go WebSocket (monorepo)
> **Son Güncelleme:** 26 Nisan 2026

---

## 🏗️ Teknoloji Yığını

| Katman | Teknoloji |
|---|---|
| Backend | Laravel 11, PHP 8.2+ |
| E-Commerce | Bagisto (modüler, `packages/Webkul/*` — 30+ modül) |
| Sunucu | Laravel Octane + Swoole (`--watch` modu aktif) |
| Veritabanı | MySQL 8.0 |
| Cache / Queue / Session | Redis |
| Arama Motoru | Elasticsearch 7.17 (⚠️ client `^8.10`) |
| WebSocket | Go (Gorilla WebSocket) + Redis opaque token auth |
| Realtime (Laravel) | Laravel Reverb |
| Frontend | Inertia.js, Vite, Tailwind CSS |
| Konteyner | Docker Compose (Laravel Sail tabanlı) |
| Mail (dev) | Mailpit |

### Bagisto Modülleri (`packages/Webkul/`)
Admin, Shop, Product, Category, Checkout, Customer, Sales, Inventory, Payment, Paypal, Shipping, Attribute, Core, DataGrid, DataTransfer, CMS, CartRule, CatalogRule, BookingProduct, Marketing, Notification, Installer, Rule, Sitemap, SocialLogin, SocialShare, GDPR, FPC, DebugBar, MagicAI

---

## 🐳 Docker Servisleri

| Servis | Port | Açıklama |
|---|---|---|
| `laravel.test` | 80, 443, 5173 | Ana uygulama (Octane/Swoole, Sail 8.3) |
| `mysql` | 3306 | Veritabanı |
| `redis` | 6379 | Cache, Session, Queue |
| `elasticsearch` | 9200, 9300 | Ürün arama motoru |
| `kibana` | 5601 | Elasticsearch yönetim paneli |
| `websocket` | 8081 | Go WebSocket sunucusu (`../websocket-server`) |
| `mailpit` | 1025, 8025 | Geliştirme mail sunucusu |

---

## 📂 Kurulum Durumu (Snapshot — 20 Nisan 2026)

| Kontrol | Durum |
|---|---|
| `.env` | ✅ Mevcut (gitignored) |
| `.env.example.local` | ✅ Versiyon kontrollü şablon — `cp .env.example.local .env` (`.env.local` adı Laravel `APP_ENV=local` override ile çakıştığı için bu isim kullanılır) |
| `vendor/` | ✅ Yüklü (`composer install`, 192 paket) |
| `node_modules/` | ✅ Yüklü (`npm install`, 72 paket) |
| `APP_KEY` | ✅ Üretilmiş |
| `websocket-server/` | ✅ Monorepo içinde (Go 1.23, Gorilla WS) |
| Docker stack | ✅ 7 servis up (`laravel.test`, `mysql`, `redis`, `elasticsearch`, `kibana`, `mailpit`, `websocket`) |
| Bagisto install | ✅ 166 migration + seed tamamlandı |
| Admin | ✅ `admin@m365.local` / `admin123` (ID=2, rotate edilmeli) |
| Git | ✅ `main` branch |

### Sıfırdan Kurulum (Fresh Install)

Yeni bir makinede (veya temiz clone'da) hızlı kurulum:

```bash
# 1) .env şablonundan başlat
cp .env.example.local .env

# 2) Bağımlılıklar (Sail container içinden)
docker compose run --rm laravel.test composer install --ignore-platform-reqs --no-scripts
docker compose run --rm laravel.test npm install

# 3) Docker servislerini başlat
docker compose up -d

# 4) APP_KEY üret (idempotent)
docker compose exec laravel.test php artisan key:generate

# 5) Bagisto kur (migration + seed + admin)
docker compose exec laravel.test php artisan bagisto:install

# 6) (Opsiyonel) Elasticsearch indeksleme
docker compose exec laravel.test php artisan indexer:index --mode=full

# 7) Frontend asset build
docker compose exec laravel.test npm run build
```

**Erişim URL'leri** (port forward `.env.example.local` / `APP_PORT=8090` ile uyumlu):
- Storefront: http://localhost:8090
- Admin: http://localhost:8090/admin/login
- Mailpit UI: http://localhost:18025
- Kibana: http://localhost:5601
- Go WebSocket: ws://localhost:8081

> ⚠️ **Not:** `composer install` sonrası `kalnoy/nestedset` patch'i kaybolur (Laravel 11 ↔ upstream uyumsuzluğu). Kalıcı çözüm için `cweagans/composer-patches` eklenmeli. Geçici çözüm: `patches/` dizinindeki dosyayı manuel uygula.

---

## 🚀 Sık Kullanılan Komutlar

### Docker
```bash
docker compose up -d
docker compose down
docker compose restart websocket
docker compose build websocket
docker logs -f go-websocket
```

### Laravel (Sail)
```bash
./vendor/bin/sail artisan <komut>

# Elasticsearch indeksleme
./vendor/bin/sail artisan indexer:index --type=elastic --mode=full
./vendor/bin/sail artisan indexer:index --mode=full   # Tümü: price, inventory, flat, elastic

# Cache
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan optimize:clear

# DB
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

# Queue & Reverb
./vendor/bin/sail artisan queue:work
./vendor/bin/sail artisan reverb:start

# Octane (watch modu zaten supervisor ile çalışıyor)
./vendor/bin/sail artisan octane:reload
```

---

## 📁 Önemli Dizinler

```
m/  (workspace root — /home/meteyar/projects/m)  ← MONOREPO
├── app/                         # Laravel uygulama katmanı (ince, Bagisto mantığı packages'de)
├── packages/Webkul/             # ✨ Bagisto modülleri (gerçek iş mantığı burada)
├── config/                      # Laravel + Bagisto config (elasticsearch.php, concord.php vb.)
├── database/                    # Migration, seed, factory
├── docker/swoole/               # Swoole Dockerfile (custom)
├── docker-compose.yml           # Sail + custom servisler (websocket dahil)
├── routes/                      # web.php, api.php
├── resources/                   # Blade, JS, CSS (admin + shop ayrı)
├── lang/                        # 22+ dil dosyası
├── public/                      # Vite build çıktıları + storage links
├── storage/                     # Log, cache, session, uploads
├── tests/                       # PHPUnit testleri
├── websocket-server/            # ✅ Go WebSocket sunucusu (monorepo, Nisan 2026'da entegre edildi)
│   ├── server.go                # Hub/Room/Client mimarisi, Redis opaque token auth
│   ├── server.old               # Eski sürüm (referans)
│   ├── Dockerfile               # Multi-stage Go 1.23 Alpine build
│   ├── go.mod / go.sum          # Go bağımlılıkları (gorilla/websocket, go-redis/v9)
│   └── k6/                      # Yük testi scriptleri
├── .env.example                 # ⚠️ .env henüz oluşturulmamış
└── vite.config.js               # Vite config (Tailwind v4)
```

### Monorepo Notu
Go WebSocket sunucusu ayrı repo iken **20 Nisan 2026**'da bu repoya entegre edildi.
- Ayrı `go.mod` (module adı: `websocket_server`) — PHP ve Go bağımlılıkları birbirine karışmaz.
- Tek `docker-compose.yml` her iki servisi de build/start eder.
- Tek git repo → aynı PR içinde cross-servis değişiklikler (örn. token formatı) atomik kalır.

---

## 🐞 Bilinen Sorunlar & Risk Alanları

### 🔴 Kritik — Kurulum Engelleyicileri ~~(20 Nisan 2026'da çözüldü)~~

| # | Sorun | Eski Durum | Güncel |
|---|---|---|---|
| 1 | ~~`vendor/` yok~~ | composer install edilmemişti | ✅ `composer install` tamamlandı |
| 2 | ~~`.env` yok~~ | template yoktu | ✅ `.env.example.local` şablonu, `cp .env.example.local .env` |
| 3 | ~~`../websocket-server` dış repo~~ | ayrı repo, monorepo değildi | ✅ Monorepo içinde (`./websocket-server/`) |

### 🟡 Yapılandırma Uyumsuzlukları

| # | Sorun | Öneri |
|---|---|---|
| 4 | Elasticsearch server `7.17.0` vs composer client `^8.10` | Server'ı `8.x`'e yükselt **veya** client'ı `^7.17` olarak kilitle |
| 5 | `APP_TIMEZONE=Asia/Kolkata` (Bagisto varsayılanı) | TR için `Europe/Istanbul` |
| 6 | `APP_CURRENCY=USD` | Proje TR pazarı için `TRY` |
| 7 | `.env.example`'da `ELASTICSEARCH_*`, `OCTANE_SERVER`, `REVERB_*` yok | Bagisto doc'larına göre ekle |
| 8 | `BROADCAST_CONNECTION=log`, `QUEUE_CONNECTION=sync`, `CACHE_STORE=file` | Redis servisi var → prod için `redis` kullan |

### 🟢 Geliştirme / Kalite

- `tests/` klasörü var ama CI kurulu değil.
- `phpunit.xml` mevcut, fakat coverage raporu yok.
- `.env.example` içinde `DB_DATABASE` boş — ilk setup'ta yanlışlıkla `php artisan migrate` çalıştırılırsa root DB'ye yazma riski.
- Octane `--watch` modu üretimde bırakılmamalı (dosya izleme + bellek).
- `APP_DEBUG=true` + `APP_DEBUG_ALLOWED_IPS=` boş → prod'a geçmeden kapatılmalı.

---

## 🔧 Hızlı Çözüm Notları

### Elasticsearch "index_not_found" Hatası
```bash
./vendor/bin/sail artisan indexer:index --type=elastic --mode=full
```

### Octane tekrar yükle (kod değişikliklerinde)
```bash
./vendor/bin/sail artisan octane:reload
```

### WebSocket servisini geçici devre dışı bırak
`docker-compose.yml`'de `websocket:` bloğunu yorum satırı yap veya `../websocket-server` repo'sunu klonla.

---

## 🎯 Auction (Mezat) Mimarisi

> **Strateji:** "Koru + Tamamla" — kullanıcının özelleştirdiği blade'lere (ana sayfa, `create-auction.blade.php`, admin dashboard blade'leri) dokunulmadı. Eksik parçalar additive migration, yeni controller'lar, yeni blade'ler ve Redis/Lua/Go WS köprüsü ile tamamlandı.

### Veri Akışı (High-Level)
```
[Müşteri] --POST /bid--> [Laravel BidController]
                            └─> [BidService.place()]
                                  ├─> Redis Lua EVAL (atomic)
                                  │     ├─ rate limit check
                                  │     ├─ status / end_at check
                                  │     ├─ min_increment check
                                  │     ├─ HSET auction:{id} current_price, top_bidder_id
                                  │     ├─ ZADD auction:{id}:leaderboard
                                  │     └─ PUBLISH auction:{id}:bids {payload}
                                  └─> MySQL TX: INSERT bids + UPDATE auctions.current_price

[Go WS Server]
  └─ PSUBSCRIBE auction:*:bids
       └─> Hub.BroadcastToRoom("auction:{id}", msg)
             └─> tüm room client'larına WS frame
```

### Redis Anahtar Şeması (`redis.auction` connection — **prefix'siz**)
| Key | Tip | İçerik | TTL |
|---|---|---|---|
| `auction:{id}` | HASH | `status`, `current_price`, `min_increment`, `end_at`, `top_bidder_id`, `top_bid_at` | `end_at + 24h` |
| `auction:{id}:leaderboard` | ZSET | `customer_id` → `amount` | `end_at + 24h` |
| `auction:{id}:bids` | PubSub channel | `{type:"bid", auction_id, customer_id, amount, ts}` | — |
| `ratelimit:bid:{customer}:{minute}` | STRING (INCR) | bid sayacı | 60s |
| `opaque:token:{token}` | STRING (JSON) | `{"user_id":N, "room_id":"auction:{id}"}` | 60s |

> ⚠️ **Önemli:** Laravel default Redis bağlantısı `m365_database_` prefix ekler. Go WS ham anahtarlarla çalışır. Bu yüzden `config/database.php` içinde ayrı `redis.auction` connection'ı (`options.prefix=''`) tanımlıdır ve tüm auction kodu **bu bağlantıyı** kullanır.

### BidService Lua Script (`App\Services\Auction\BidService::LUA_PLACE_BID`)
Atomik kontroller (tek roundtrip):
1. `HGETALL auction:{id}` → yoksa `not_found`.
2. `status == "active"` değilse `not_active`.
3. `now > end_at` ise `ended`.
4. `amount < current_price + min_increment` ise `too_low`.
5. `INCR ratelimit:bid:{customer}:{minute}`; `> 5` ise `rate_limited`.
6. `HSET current_price, top_bidder_id, top_bid_at` + `ZADD leaderboard` + `PUBLISH bids`.
7. Return `{status:"ok", new_price}`.

Redis'ten `ok` dönerse BidService **MySQL transaction** açar: `bids` tablosuna append-only insert, `auctions.current_price` update.

### WS Token Flow
1. Müşteri `POST /auctions/{id}/ws-token` (session auth).
2. `WsTokenService::issue()` 48 karakterlik opaque token üretir, `opaque:token:{token}` → `{"user_id":customer.id, "room_id":"auction:{id}"}` (TTL 60s).
3. Client `ws://.../ws?token={token}&room=auction:{id}` ile Go sunucusuna bağlanır.
4. Go `validateOpaqueToken` Redis'ten key'i okur, `room_id` eşleşiyorsa token'ı **siler** (one-time use), bağlantıyı kabul eder.

### Auction Lifecycle
```
pending --[admin approve]--> approved
approved --[start_time ≥ now + auction:activate --batch]--> active
active --[end_time ≥ now + auction:close]--> closed
any --[admin cancel]--> cancelled
any --[admin reject]--> rejected  (sadece pending → rejected)
```

Scheduler (`bootstrap/app.php`):
- `auction:activate --batch` → her dakika
- `auction:close` → her dakika

### Events & Notifications
| Event | Payload | Broadcast | Listener |
|---|---|---|---|
| `ActionActivated` | auction id, price, end_at | `PrivateChannel('auction.{id})` | — |
| `AuctionClosed` | auction, winner_bid | `PrivateChannel('auction.{id})` | `SendAuctionWonNotification` (ShouldQueue) → `AuctionWonMail` |

> Not: `ActionActivated` sınıf adı **typo** içeriyor ama çağıran kodu kırmamak için korundu.

### Önemli Dosyalar
| Rol | Dosya |
|---|---|
| Envanter | `docs/auction-legacy-inventory.md` |
| Config | `config/auction.php`, `config/database.php` (redis.auction) |
| Service | `app/Services/Auction/BidService.php`, `app/Services/Auction/WsTokenService.php` |
| Controller (shop) | `app/Http/Controllers/AuctionController.php` (eski), `AuctionPageController.php` (yeni, detail/list/ws-token) |
| Controller (shop api) | `packages/Webkul/Shop/src/Http/Controllers/API/BidController.php` |
| Controller (admin) | `app/Http/Controllers/Admin/AuctionModerationController.php` |
| Commands | `app/Console/Commands/ActivateAuctionCommand.php`, `CloseAuctionCommand.php` |
| Events/Mail | `app/Events/ActionActivated.php`, `app/Events/AuctionClosed.php`, `app/Mail/AuctionWonMail.php`, `app/Listeners/SendAuctionWonNotification.php` |
| Blade (shop) | `packages/Webkul/Shop/src/Resources/views/auctions/{index,show}.blade.php` |
| Blade (admin) | `resources/views/admin/auctions/{index,show}.blade.php`, `resources/views/emails/auction-won.blade.php` |
| Go WS | `websocket-server/server.go` (`runRedisBridge` goroutine) |

### Açık Konular (QA sonrası)
- ~~⚠️ **ViteManifestNotFoundException**~~ → ✅ **Çözüldü (20 Nisan 2026, akşam).** Kök neden: `bagistoplus/visual` paketi `visual-debut` temasını fallback olarak aktive ediyordu, çünkü yeni auction route'larında `theme/locale/currency` middleware'i eksikti. Düzeltme:
  - `routes/web.php` → auction route'ları `['web','theme','locale','currency']` middleware grubuna alındı.
  - `public/themes/shop/default/build/` → git HEAD'den geri yüklendi (`app-BoUQhNYM.css`).
  - `channels.theme` = `default` (DB'den doğrulandı).
- ~~Top bar ve "Satış yap" butonu görünmüyor~~ → ✅ **Çözüldü (20 Nisan 2026, akşam).** `components/layouts/header/index.blade.php` içindeki `@if (locales>1 || currencies>1)` koşulu kaldırıldı; topbar artık her zaman render ediliyor.
- ~~`/customer/create-auction` 404 veriyor~~ → ✅ **Çözüldü (20 Nisan 2026, akşam).** `HomeController::createAuction()` içindeki dead-code `$this->productRepository->findOrFail(17)` kaldırıldı (blade `$product` değişkenini hiç kullanmıyordu).
- Admin-side bid history view, winner fulfillment/order creation entegrasyonu Faz dışı bırakıldı.
- Ödeme akışına (deposit) bağlanma ve "teminat iadesi" senaryosu planlanmadı.

---

## 📎 İlgili Belgeler

- `README.md` — Bagisto orijinal dokümantasyon
- `UPGRADE.md` — Bagisto sürüm yükseltme notları
- `CONTRIBUTING.md`, `SECURITY.md`, `CODE_OF_CONDUCT.md`
- `CHANGELOG.md` — Bu projedeki değişikliklerin geçmişi (aşağıda)
