# Changelog — m365 (Bagisto)

Bu dosya, **m365** projesindeki tüm kayda değer değişiklikleri izler.
Format: [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) ·
Sürümleme: [SemVer](https://semver.org/lang/tr/)

---

## [Unreleased]

### Fixed — Tema & UI Kurtarma (20 Nisan 2026, akşam)
- **Eski tasarım geri kazanıldı.** `bagistoplus/visual` paketinin `visual-debut` temasını fallback olarak aktive etmesi nedeniyle giriş sonrası sayfalarda yabancı tema görünüyordu. Kök neden: yeni auction route'larında Bagisto'nun `theme/locale/currency` middleware'i eksikti.
  - `routes/web.php` → `Route::middleware(['web','theme','locale','currency'])` grubu auction route'larına uygulandı.
  - `public/themes/shop/default/build/` → git HEAD'den geri yüklendi (`app-BoUQhNYM.css`), yetim `npm run build` artifactları silindi.
  - `channels.theme` = `default` olarak doğrulandı.
- **Top bar + "Satış yap" butonu geri getirildi.** `packages/Webkul/Shop/src/Resources/views/components/layouts/header/index.blade.php` içindeki `@if (locales > 1 || currencies > 1)` koşulu kaldırıldı; topbar artık tek locale/currency'de de render ediliyor.
- **`/customer/create-auction` 404'ü düzeltildi.** `HomeController::createAuction()` içindeki dead-code `$this->productRepository->findOrFail(17)` kaldırıldı. Blade şablonu `$product` değişkenini zaten hiç kullanmıyordu; sabit kodlu ID=17 DB'de olmadığı için 404 atıyordu.

### Added — Kurulum Kolaylığı (20 Nisan 2026, akşam)
- **`.env.local` repo'ya eklendi** (versiyon kontrollü şablon). `APP_KEY` ve Reverb secret'ları boş bırakıldı; docker-compose hostname'leri (mysql/redis/mailpit/elasticsearch) ve port forward'lar (`APP_PORT=8090`, `FORWARD_DB_PORT=33306` vb.) hazır. Kurulum: `cp .env.local .env && php artisan key:generate`.
- **`PROJECT_MEMORY.md` kurulum bölümü güncellendi** — "Sıfırdan Kurulum" 7 adımlık tek bloğa indirgendi, docker-compose akışıyla uyumlu.

### Added — Auction "Koru + Tamamla" (20 Nisan 2026)
- **Faz 0 — Envanter:** [docs/auction-legacy-inventory.md](docs/auction-legacy-inventory.md) oluşturuldu. Korunacak (dokunulmayacak) blade'ler, mevcut controller/model/command/route haritası ve 14 bilinen bug envantere alındı.
- **Faz 1 — Schema:** Additive migration `2026_04_20_000000_extend_auctions_and_create_bids_table.php`:
  - `auctions` tablosuna eksik kolonlar eklendi: `title`, `description`, `image`, `start_price`, `current_price`, `min_increment`, `winner_customer_id`, `closed_at`.
  - Yeni `bids` tablosu: `(auction_id, customer_id, amount)` + `(auction_id, amount DESC)` ve `(auction_id, created_at)` index'leri.
  - `App\Models\Auction` → `$fillable`, status constants, `bids()` HasMany, `winner()`/`customer()` relations, `isLive()/isUpcoming()/isClosed()` helpers.
  - Yeni `App\Models\Bid` modeli.
- **Faz 2 — Atomic Bid Service:**
  - `App\Services\Auction\BidService` → **Redis Lua script** ile atomik bid placement: status/end_at/rate-limit/min_increment kontrolü + `HSET current_price` + `ZADD leaderboard` + `PUBLISH auction:{id}:bids`. Redis kabul ettikten sonra MySQL'e append-only `bids` insert + `auctions.current_price` update.
  - Rate limit: customer başına 5 teklif / 60 saniye (Redis INCR + EXPIRE).
  - `packages/Webkul/Shop/src/Http/Controllers/API/BidController` (placeholder) dolduruldu: validation, customer auth, self-bid guard, durum kodu eşleme (429/422/404).
- **Faz 3 — Shop auction detay sayfası:**
  - Yeni `app/Http/Controllers/AuctionPageController@show` + `packages/Webkul/Shop/src/Resources/views/auctions/show.blade.php`.
  - Canlı countdown (setInterval), Go WS üzerinden live bid feed, bid form, `current_price` Redis hot-state'ten okuma, CSRF korumalı bid POST.
- **Faz 4 — Auction listing:**
  - `GET /auctions` → `AuctionPageController@index` + `shop::auctions.index` blade. Aktif / Yakında / Bitti filtreleri, pagination. Ana sayfa `home/index.blade.php` **hiç dokunulmadı** (kullanıcının tasarım koruma isteği).
- **Faz 5 — Go WS ↔ Laravel köprüsü:**
  - `websocket-server/server.go` içine yeni `runRedisBridge` goroutine: `PSUBSCRIBE auction:*:bids` ile Laravel yayınlarını yakalayıp ilgili room'a broadcast eder.
  - `App\Services\Auction\WsTokenService` → Go'nun beklediği şemada opaque token (`opaque:token:{token}` → `{"user_id","room_id"}`, TTL 60s) üretir.
  - `POST /auctions/{id}/ws-token` (customer auth) endpoint'i.
  - **Kritik fix:** Yeni `config/database.php` içinde `redis.auction` bağlantısı — prefix'siz (`options.prefix=''`). Laravel default connection `m365_database_` prefix eklediği için Go WS ile ham anahtar uzayında tutarsızlık vardı. BidService, WsTokenService, AuctionPageController, Admin moderation ve CloseAuctionCommand bu bağlantıyı kullanıyor.
  - `config/auction.php` (WS endpoint, rate-limit parametreleri, Redis key şeması).
- **Faz 6 — Admin moderation:**
  - `App\Http\Controllers\Admin\AuctionModerationController`: `index/show/approve/activate/reject/cancel`.
  - `resources/views/admin/auctions/{index,show}.blade.php` — `x-admin::layouts` kullanıyor, `Webkul\Admin` paketindeki dashboard blade'lerine **dokunulmadı**.
  - Route grubu `routes/web.php` altında admin middleware + admin URL prefix ile.
- **Faz 7 — Scheduler + kapanış:**
  - `auction:activate` komutu yeniden yazıldı: tek-id (geriye dönük uyum) ve `--batch` modu (zamanı gelmiş pending/approved mezatları aktif eder, Redis warm-up çalıştırır, `ActionActivated` event fırlatır).
  - Yeni `auction:close` komutu: süresi dolmuş aktif mezatları kapatır, en yüksek teklifi kazanan yapar, Redis pub/sub üzerinden close event yayınlar, `AuctionClosed` event fırlatır.
  - `bootstrap/app.php` scheduler'a her iki komut her dakika `withoutOverlapping()` ile bağlandı.
  - `App\Events\ActionActivated` tamamlandı: `PrivateChannel('auction.{id}')`, `broadcastAs()`, `broadcastWith()` payload. (İsim typo'su çağıran kodu bozmamak için korundu.)
  - Yeni `App\Events\AuctionClosed` + `App\Listeners\SendAuctionWonNotification` (ShouldQueue) + `App\Mail\AuctionWonMail` + `resources/views/emails/auction-won.blade.php`. Listener auto-discovery `withEvents(discover: [...])` ile aktif.
- **Faz 8 — QA:**
  - E2E tinker scriptleriyle doğrulandı: Lua atomic bid (ok/too_low/rate_limited), leaderboard ZSET, Redis → node.js WS client broadcast, auction:close komutu + winner seçimi, MySQL ledger'a bid insert, `auctions.current_price` update.

### Fixed — Auction
- **Schema mismatch**: `AuctionController@store` `auctions` tablosunda olmayan kolonlara (`title/description/image/start_price/current_price/min_increment`) INSERT ediyordu — Faz 1 migration'ı schema'yı tamamladı. `store()` metodu artık çalışır durumda.
- **Redis prefix tutarsızlığı**: Laravel default `REDIS_PREFIX=m365_database_` ile Go WS'in beklediği ham anahtar uzayı arasında köprü — prefix'siz `redis.auction` connection'ı eklendi.
- **`Webkul\Shop\Http\Controllers\API\BidController@make` placeholder** (`return true;`) tam implementasyonla değiştirildi.
- **`ActionActivated` event** `PrivateChannel('channel-name')` placeholder → `PrivateChannel('auction.{id}')`.
- **`ActivateAuctionCommand`** içinde `event()` satırı yorum satırındaydı → düzeltildi ve `--batch` modu eklendi.

### Added
- `PROJECT_MEMORY.md` ve `CHANGELOG.md` dosyaları eklendi.
- `m365.code-workspace` workspace dosyası hazırlandı.
- **`.env` dosyası oluşturuldu** (APP_NAME=m365, APP_KEY set, Reverb credentials, Pusher şablonu, `ELASTICSEARCH_HOST=http://elasticsearch:9200`, TR locale/currency, Redis driver'lar, WWWUSER/WWWGROUP=1000).
- **DB credentials**: `m365` / `m365` / `m365_dev_2026` (docker-compose MySQL auto-provision).
- **Port forward'ları** (`APP_PORT=8090`, `FORWARD_DB_PORT=33306`, `FORWARD_REDIS_PORT=16379`, `FORWARD_MAILPIT_PORT=11025`, `FORWARD_MAILPIT_DASHBOARD_PORT=18025`) — adorelgo projesiyle çakışmaları önlemek için.
- **Composer install tamamlandı** (192 paket, Sail PHP 8.3 container üzerinden 2.5dk'da, `--no-scripts --ignore-platform-reqs`). Autoload generate edildi.
- **npm install tamamlandı** (72 paket).
- **Docker Compose stack ayağa kaldırıldı** — 7 servis (`laravel.test`, `mysql`, `redis`, `elasticsearch`, `kibana`, `mailpit`, `websocket`). Sail image ilk build'i ~8.5dk.
- **Bagisto kurulumu tamamlandı** (`php artisan bagisto:install --skip-admin-creation`):
  - 166 migration başarıyla koştu (22 sn).
  - Basic data seed edildi (varsayılan admin dahil — muhtemelen ID=1, `admin@example.com`/`admin123`).
  - `public/storage` symlink oluşturuldu.
  - Bootstrap cache temizlendi.
- **İkinci admin oluşturuldu**: `admin@m365.local` / `admin123` (ID=2, Role=Administrator ID=2).
- **Go WebSocket sunucusu projeye entegre edildi** (monorepo).

### Fixed
- **Laravel 11 ↔ `kalnoy/nestedset` uyumsuzluğu** (Bagisto upstream bug): `NodeTrait::bootNodeTrait()` içindeki `static::whenBooted(...)` çağrısı Laravel 12+ API'si — Octane boot'u `FATAL state`'e düşürüyordu. `vendor/kalnoy/nestedset/src/NodeTrait.php` içinde closure doğrudan çalıştırılacak şekilde patch'lendi. **⚠️ Bu patch `composer install` sonrası kaybolur** — kalıcı çözüm için `cweagans/composer-patches` eklenmesi öneriliyor.
- **APP_PORT çakışması**: `docker-compose.yml`'deki `SUPERVISOR_PHP_COMMAND` hem host port'u hem container-içi port'u aynı değişkenden alıyordu. Octane container içinde sabit `--port=80` dinleyecek şekilde düzeltildi (host tarafı `APP_PORT` ile ayrı).
- **`composer.json`** → `kalnoy/nestedset` constraint `^6.0` → `6.0.11` olarak pin'lendi (upstream `v6.0.12+` `whenBooted`'u daha agresif kullanıyor).
  - Zip içinden çıkmış `websocket-server/` dizini repo köküne taşındı.
  - İç içe yapı düzleştirildi (`websocket-server/websocket-server/*` → `websocket-server/*`).
  - `__MACOSX/`, `*:Zone.Identifier`, `.DS_Store` kalıntıları temizlendi.
  - `docker-compose.yml` → `websocket` servisi `context` yolu `../websocket-server` → `./websocket-server` olarak güncellendi.
  - `m365.code-workspace` → "websocket-server (Go)" klasörü root-relative olarak güncellendi.
  - `.gitignore` → Go binary, OS ve IDE artifaktları eklendi.

### Analiz Notları (20 Nisan 2026)
- Kurulum eksiklikleri tespit edildi:
  - `.env` henüz oluşturulmamış
  - `vendor/` (composer) ve `node_modules/` (npm) yüklü değil
  - `../websocket-server` repo'su workspace dışında, `docker-compose` build fail edebilir
- Yapılandırma uyumsuzlukları:
  - Elasticsearch server `7.17` ↔ client `^8.10` sürüm uyumsuzluğu riski
  - `APP_TIMEZONE=Asia/Kolkata`, `APP_CURRENCY=USD` → TR için güncellenmeli
  - `.env.example`'da `ELASTICSEARCH_*`, `OCTANE_SERVER`, `REVERB_*` değişkenleri yok
  - `QUEUE_CONNECTION=sync`, `CACHE_STORE=file`, `BROADCAST_CONNECTION=log` → Redis servisi çalışır durumdayken prod için Redis'e taşınmalı

### Deprecation Uyarıları
- `paypal/paypal-checkout-sdk` **abandoned** — Bagisto upstream güncellemesi takip edilmeli (`paypal/paypal-server-sdk` önerilen).
- `paypal/paypalhttp` **abandoned** — replacement yok.

### TODO (öncelik sırasıyla)
- [x] ~~`composer install && npm install`~~
- [x] ~~`.env` oluştur ve `APP_KEY` üret~~
- [x] ~~DB credentials~~ (`m365`/`m365`/`m365_dev_2026`)
- [x] ~~`docker compose up -d` ile servisleri başlat~~
- [x] ~~Migration + seed~~ (`bagisto:install` ile)
- [x] ~~`APP_TIMEZONE` → `Europe/Istanbul`~~
- [x] ~~`.env.local` versiyon kontrollü şablon~~ (sıfırdan kurulum için)
- [x] ~~Tema çakışması (`visual-debut` fallback) giderildi~~
- [x] ~~Top bar + "Satış yap" butonu geri~~
- [x] ~~`/customer/create-auction` 404 fix~~
- [ ] **`cweagans/composer-patches` ekle** → nestedset patch'i kalıcı yap (composer install sonrası kaybolmasın)
- [ ] `./vendor/bin/sail artisan indexer:index --mode=full` (elasticsearch `products_*_index` oluştur)
- [ ] Elasticsearch sürüm uyumsuzluğunu çöz (server 7.17 ↔ client 8.x)
- [ ] WebSocket stratejisi karar (Go / Reverb / her ikisi)
- [ ] Default seeded admin'in (ID=1) credentials'larını doğrula ve rotate et
- [ ] `admin@m365.local` için güçlü şifre (şu an `admin123`)
- [ ] Admin paneline HTTP ile giriş testi (E2E)
- [ ] Üretim için `APP_DEBUG=false`

---

## [0.1.0] — 21 Mart 2026

### Added
- **WebSocket Docker entegrasyonu**
  - `websocket-server/Dockerfile` (multi-stage Alpine Go build)
  - `server.go` — Redis adresi `REDIS_HOST` env var'dan okunacak şekilde güncellendi
  - `docker-compose.yml` — `websocket` servisi (port 8081, Redis bağımlı)
- **Elasticsearch indeksleme** — `products_default_tr_index` ilk kez oluşturuldu
  - Komut: `indexer:index --type=elastic --mode=full`

### Fixed
- `websocket-server/go.mod` — Go sürümü düzeltildi (`1.25.5` → `1.23`)

### Changed
- Proje temizliği: Eski `socket` klasörü silindi, `sockket2` → `websocket-server` olarak yeniden adlandırıldı

---

## [0.0.1] — İlk Commit

### Added
- Bagisto Laravel E-Commerce temel kurulumu
- 30+ Webkul modülü (`packages/Webkul/*`)
- Laravel Sail + Octane (Swoole) yapılandırması
- MySQL, Redis, Elasticsearch 7.17, Kibana, Mailpit Docker servisleri
- Inertia.js + Vite + Tailwind frontend
- 22+ dil desteği (`lang/`)
- Bagisto orijinal dokümantasyon (`README.md`, `UPGRADE.md`, `CONTRIBUTING.md`)

---

### Notlar
- Her anlamlı değişiklikten sonra bu dosyayı güncelle.
- Bölümler: **Added**, **Changed**, **Deprecated**, **Removed**, **Fixed**, **Security**.
- Tarihleri `YYYY-AA-GG` veya `GG Ay YYYY` formatında tut (Türkçe ay adları kabul).
