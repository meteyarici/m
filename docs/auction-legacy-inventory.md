# m365 Auction — Legacy Inventory (Faz 0)

> Bu doküman, mevcut yarım kalmış "canlı mezat" sisteminin donmuş envanteridir.
> Aşağıdaki **Korunacak Dosyalar** listesine giren dosyalar, "Koru + Tamamla"
> stratejisi gereği *hiç dokunulmayacak*. Bir PR bu listedeki bir dosyaya dokunuyorsa
> incelemede kırmızı bayrak açılmalıdır.

## 1. Mevcut Auction Bileşenleri Haritası

### Domain / Model
| Dosya | Açıklama |
| --- | --- |
| `app/Models/Auction.php` | 30 satır, sade Eloquent model. `start_at`, `end_at` cast'li. `product()` belongsTo ilişkisi var. `getStartAtFormattedAttribute`, `getEndAtFormattedAttribute` accessor'ları mevcut. |
| `app/Models/Bid.php` | **YOK** — Faz 1'de oluşturulacak. |

### Migration
| Dosya | Açıklama |
| --- | --- |
| `database/migrations/2025_12_10_024826_create_auctions_table.php` | Tek auction migration'ı. Kolonlar: `id, user_id, product_id, start_at, end_at, min_price, buy_now_price, bid_increment, is_buy_now_available, status`. FK: `customers(id)`, `products(id)`. |
| `bids` table migration | **YOK** — Faz 1'de oluşturulacak. |

### HTTP Controllers
| Dosya | Yöntem | Açıklama |
| --- | --- | --- |
| `app/Http/Controllers/AuctionController.php` | `upload()` | **SAĞLAM.** IP bazlı Redis rate-limit (10/60s), 5MB image upload, `storage/auctions/tmp` dizinine yazıyor. **Koruma listesinde.** |
| `app/Http/Controllers/AuctionController.php` | `store()` | **BUGGY.** `auctions` tablosuna olmayan kolonlara (`title, description, image, start_price, current_price, min_increment`) INSERT ediyor. Faz 1 migration'ı ile schema tamamlanınca sorun çözülecek. Product/attribute/category/inventory insert mantığı aynen kalacak. |
| `packages/Webkul/Shop/src/Http/Controllers/API/BidController.php` | `make()` | **EKSİK.** `return true;` placeholder. Faz 2'de doldurulacak. |

### Routes
| Rota | Controller | Durum |
| --- | --- | --- |
| `GET customer/create-auction` → `shop.create-auction.index` | `Webkul\Shop\Http\Controllers\HomeController@createAuction` | Çalışıyor. `product_id = 17` sabiti var, ileride düzeltilebilir ama bu envantere girmiyor. |
| `POST customer/upload-images` → `shop.auction.upload` | `App\Http\Controllers\AuctionController@upload` | Çalışıyor. |
| `POST customer/auction/store` → `shop.auction.store` | `App\Http\Controllers\AuctionController@store` | Schema düzeltilince çalışacak. |
| `POST api/auction/bid` → `shop.api.auction.bid.make` | `Webkul\Shop\Http\Controllers\API\BidController@make` | Placeholder. |
| `GET /auctions` ve `GET /auctions/{id}` | **YOK** | Faz 3 & 4'te oluşturulacak. |

### Console Commands
| Dosya | Durum |
| --- | --- |
| `app/Console/Commands/ActivateAuctionCommand.php` | Tek-id argümanlı `auction:activate {auction_id}`. `event(new ActionActivated($auction))` satırı yorum satırına alınmış. Faz 7'de batch mod eklenecek, event düzeltilecek. |
| `app/Console/Commands/CloseAuctionCommand.php` | **YOK.** Faz 7'de oluşturulacak. |

### Events
| Dosya | Durum |
| --- | --- |
| `app/Events/ActionActivated.php` | İsim tipo (`ActionActivated`, doğrusu `AuctionActivated`). `PrivateChannel('channel-name')` placeholder. `__construct` boş. Faz 7'de tamamlanacak (dosya adını kırmamak için sınıf adı korunacak, sadece içerik doldurulacak). |
| `app/Events/AuctionClosed.php` | **YOK.** Faz 7'de oluşturulacak. |

### Go WebSocket Server
| Dosya | Durum |
| --- | --- |
| `websocket-server/server.go` | Gorilla WS + Hub + room mimarisi. Opaque token auth (`opaque:token:*`). `:8081` üzerinde çalışıyor. **Koruma listesinde** — sadece Faz 5'te yeni bir goroutine eklenecek (Redis PSUBSCRIBE köprüsü). |

### Redis Hot-State (mevcut kullanımlar)
- `auction:upload:{ip}` — rate-limit sayacı (TTL 60s)
- `auction:{auction_id}` — HASH (auction meta). TTL = `end_at + 24h`
- `opaque:token:{token}` — Go WS auth token (mevcut mekanizma)

---

## 2. Korunacak (HİÇ dokunulmayacak) Dosyalar

### Storefront Blade'ler
- `packages/Webkul/Shop/src/Resources/views/home/index.blade.php` — **960/360 split ana sayfa layout**
- `packages/Webkul/Shop/src/Resources/views/home/create-auction.blade.php` — 1786 satırlık multi-step form
- `packages/Webkul/Shop/src/Resources/views/home/contact-us.blade.php`
- `packages/Webkul/Shop/src/Resources/views/checkout/**/*.blade.php` (onepage, cart, mini-cart, summary, login, coupon, success)
- `packages/Webkul/Shop/src/Resources/views/compare/index.blade.php`
- `packages/Webkul/Shop/src/Resources/views/components/shimmer/**`
- `packages/Webkul/Shop/src/Resources/views/components/range-slider/**`
- `packages/Webkul/Shop/src/Resources/views/errors/index.blade.php`

### Admin Blade'ler
- `packages/Webkul/Admin/src/Resources/views/dashboard/**/*.blade.php` (index, over-all-details, top-customers, top-selling-products, stock-threshold-products, total-visitors, total-sales, todays-details)

### Backend (korunacak davranış)
- `app/Http/Controllers/AuctionController::upload` — Redis rate-limit + tmp upload mantığı aynen kalacak
- `app/Http/Controllers/AuctionController::store` içindeki Bagisto product/attribute/category/inventory insert dizisi (sadece `auctions` INSERT dizisi migration ile uyumlu hale getirilecek)
- `websocket-server/server.go` — mevcut Hub/rooms/opaque-token flow korunacak; yalnızca yeni bir goroutine eklenecek

---

## 3. Bilinen Buglar ve Eksikler (Planın tamamlayacağı)

| # | Bug / Eksik | Faz |
| --- | --- | --- |
| 1 | `AuctionController@store`: `title, description, image, start_price, current_price, min_increment` kolonlarına INSERT ama migration'da yok → **MySQL "Unknown column" hatası** | Faz 1 |
| 2 | `bids` tablosu yok | Faz 1 |
| 3 | `app/Models/Bid.php` yok | Faz 1 |
| 4 | `Auction` modelinde `$fillable` yok, `bids()` ilişkisi yok | Faz 1 |
| 5 | `BidController::make` placeholder; atomik bid servisi yok; rate-limit yok; Redis pub/sub yok | Faz 2 |
| 6 | Auction detay sayfası (`/auctions/{id}`) yok | Faz 3 |
| 7 | Auction listing sayfası (`/auctions`) yok | Faz 4 |
| 8 | Laravel → Redis `auction:{id}:bids` publish ile Go WS broadcast köprüsü yok | Faz 5 |
| 9 | Laravel'de WS opaque token üretici endpoint yok (`/api/auction/{id}/ws-token`) | Faz 5 |
| 10 | Admin moderation paneli yok (approve/reject/cancel) | Faz 6 |
| 11 | `ActivateAuctionCommand` scheduler'a bağlı değil, batch mode yok | Faz 7 |
| 12 | `CloseAuctionCommand` yok, winner seçme mantığı yok | Faz 7 |
| 13 | `ActionActivated` event `PrivateChannel('channel-name')` placeholder, constructor boş | Faz 7 |
| 14 | `AuctionClosed` event + winner notification mail yok | Faz 7 |

---

## 4. Kritik Kararlar

- **Paketleştirme iptal.** `Webkul\Auction` paketi oluşturmak yerine kod mevcut yerinde (`app/` + `packages/Webkul/Shop`) kalacak; tasarımı kırma riski minimize.
- **Naming:** Controller `min_increment` bekliyor, migration `bid_increment`. Çözüm: Yeni migration'da `min_increment` kolonu eklenecek, mevcut `bid_increment` geriye dönük uyum için bırakılacak (her ikisi de schema'da olacak; aktif kullanılan `min_increment` olacak).
- **BidController konumu:** `packages/Webkul/Shop/src/Http/Controllers/API/BidController.php` dosyası zaten mevcut ve `api.php` onu import ediyor. Plandaki "yeni Controller oluştur" niyeti bu **mevcut dosyayı doldurmak** şeklinde uygulanacak. İş mantığı `app/Services/Auction/BidService.php`'de yaşayacak.
- **Ana sayfa:** `home/index.blade.php`'ye "Aktif mezatlar" widget'ı **eklenmeyecek**. `/auctions` tamamen ayrı bir route.
