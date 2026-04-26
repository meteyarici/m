<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Category\Models\Category;

/**
 * Mezat (create-auction) ekranındaki 21 ana kategori ve alt kategorilerini
 * Bagisto'nun `categories` tablosuna seed'ler. Her kategori, `additional`
 * alanında `legacy_id` (frontend'deki sabit id) ile işaretlenir; bu sayede
 * blade tarafındaki eski id'ler ile DB kayıtları eşleştirilebilir.
 *
 * Tekrar çalıştırılabilir (idempotent): var olan kategori tekrar oluşturulmaz,
 * sadece eksik olanlar eklenir.
 */
class AuctionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rootId = (int) DB::table('categories')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->value('id');

        if (! $rootId) {
            $this->command?->error('Root kategori bulunamadı. Önce Bagisto kurulumunun tamamlandığından emin ol.');

            return;
        }

        $tree = $this->tree();

        $position = (int) DB::table('categories')->where('parent_id', $rootId)->max('position') ?: 0;

        foreach ($tree as $main) {
            $mainCategory = $this->ensureCategory(
                parentId: $rootId,
                legacyId: $main['id'],
                name: $main['name'],
                icon: $main['icon'] ?? null,
                position: ++$position,
            );

            $subPosition = 0;

            foreach ($main['subCategories'] ?? [] as $sub) {
                $this->ensureCategory(
                    parentId: (int) $mainCategory->id,
                    legacyId: $sub['id'],
                    name: $sub['name'],
                    icon: null,
                    position: ++$subPosition,
                );
            }
        }

        $this->command?->info('Auction kategorileri seed edildi.');
    }

    /**
     * Verilen legacy_id için kategori yoksa oluştur, varsa dön.
     */
    protected function ensureCategory(int $parentId, int $legacyId, string $name, ?string $icon, int $position)
    {
        $existingId = DB::table('categories')
            ->where('additional', 'like', '%"legacy_id":'.$legacyId.'%')
            ->value('id');

        if ($existingId) {
            return DB::table('categories')->where('id', $existingId)->first();
        }

        $slugBase = Str::slug($name) ?: 'kategori-'.$legacyId;

        $slug = $this->uniqueSlug($slugBase);

        $category = Category::create([
            'parent_id'    => $parentId,
            'position'     => $position,
            'status'       => 1,
            'display_mode' => 'products_and_description',
            'additional'   => json_encode([
                'legacy_id' => $legacyId,
                'icon'      => $icon,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        DB::table('category_translations')->insert([
            'category_id'      => $category->id,
            'name'             => $name,
            'slug'             => $slug,
            'url_path'         => '',
            'description'      => $name.' kategorisi',
            'meta_title'       => $name,
            'meta_description' => '',
            'meta_keywords'    => '',
            'locale'           => 'tr',
        ]);

        return DB::table('categories')->where('id', $category->id)->first();
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 1;

        while (DB::table('category_translations')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }

    /**
     * create-auction.blade.php'deki kategori listesinin birebir kopyası.
     */
    protected function tree(): array
    {
        return [
            ['id' => 1, 'name' => 'Figür', 'icon' => '🧸', 'subCategories' => [
                ['id' => 101, 'name' => 'Anime'],
                ['id' => 102, 'name' => 'Marvel/DC'],
                ['id' => 103, 'name' => 'Lego'],
                ['id' => 104, 'name' => 'Model Araç'],
            ]],
            ['id' => 2, 'name' => 'Ev Eşyası', 'icon' => '🏠', 'subCategories' => [
                ['id' => 201, 'name' => 'Mobilya'],
                ['id' => 202, 'name' => 'Dekorasyon'],
                ['id' => 203, 'name' => 'Aydınlatma'],
                ['id' => 204, 'name' => 'Halı/Kilim'],
            ]],
            ['id' => 3, 'name' => 'Elektronik', 'icon' => '📷', 'subCategories' => [
                ['id' => 301, 'name' => 'Fotoğraf'],
                ['id' => 302, 'name' => 'Ses Sistemleri'],
                ['id' => 303, 'name' => 'Retro Oyun'],
                ['id' => 304, 'name' => 'Telefon'],
            ]],
            ['id' => 4, 'name' => 'Emlak', 'icon' => '🏢', 'subCategories' => [
                ['id' => 401, 'name' => 'Konut'],
                ['id' => 402, 'name' => 'Arsa'],
                ['id' => 403, 'name' => 'Ticari'],
            ]],
            ['id' => 5, 'name' => 'Araç', 'icon' => '🚗', 'subCategories' => [
                ['id' => 501, 'name' => 'Otomobil'],
                ['id' => 502, 'name' => 'Motosiklet'],
                ['id' => 503, 'name' => 'Klasik'],
            ]],
            ['id' => 6, 'name' => 'Aletler', 'icon' => '🛠️', 'subCategories' => [
                ['id' => 601, 'name' => 'El Aletleri'],
                ['id' => 602, 'name' => 'Bahçe'],
                ['id' => 603, 'name' => 'Endüstriyel'],
            ]],
            ['id' => 7, 'name' => 'Tespih', 'icon' => '📿', 'subCategories' => [
                ['id' => 701, 'name' => 'Kehribar'],
                ['id' => 702, 'name' => 'Oltu'],
                ['id' => 703, 'name' => 'Kuka'],
            ]],
            ['id' => 8, 'name' => 'Sanat', 'icon' => '🎨', 'subCategories' => [
                ['id' => 801, 'name' => 'Tablo'],
                ['id' => 802, 'name' => 'Heykel'],
                ['id' => 803, 'name' => 'Hat Sanatı'],
            ]],
            ['id' => 9, 'name' => 'Saat', 'icon' => '⌚', 'subCategories' => [
                ['id' => 901, 'name' => 'Kol Saati'],
                ['id' => 902, 'name' => 'Köstekli'],
                ['id' => 903, 'name' => 'Duvar Saati'],
            ]],
            ['id' => 10, 'name' => 'Mücevher', 'icon' => '💎', 'subCategories' => [
                ['id' => 1001, 'name' => 'Altın'],
                ['id' => 1002, 'name' => 'Gümüş'],
                ['id' => 1003, 'name' => 'Yüzük'],
            ]],
            ['id' => 11, 'name' => 'Kitap', 'icon' => '📚', 'subCategories' => [
                ['id' => 1101, 'name' => 'Nadir Eserler'],
                ['id' => 1102, 'name' => 'İmzalılar'],
                ['id' => 1103, 'name' => 'Dergi'],
            ]],
            ['id' => 12, 'name' => 'Para/Madalya', 'icon' => '🪙', 'subCategories' => [
                ['id' => 1201, 'name' => 'Eski Paralar'],
                ['id' => 1202, 'name' => 'Madalyalar'],
                ['id' => 1203, 'name' => 'Banknot'],
            ]],
            ['id' => 13, 'name' => 'Müzik', 'icon' => '🎸', 'subCategories' => [
                ['id' => 1301, 'name' => 'Enstrüman'],
                ['id' => 1302, 'name' => 'Plak'],
                ['id' => 1303, 'name' => 'CD/Kaset'],
            ]],
            ['id' => 14, 'name' => 'Spor', 'icon' => '⚽', 'subCategories' => [
                ['id' => 1401, 'name' => 'Koleksiyon Formalar'],
                ['id' => 1402, 'name' => 'Ekipman'],
                ['id' => 1403, 'name' => 'Fitness'],
            ]],
            ['id' => 15, 'name' => 'Giyim', 'icon' => '👕', 'subCategories' => [
                ['id' => 1501, 'name' => 'Vintage'],
                ['id' => 1502, 'name' => 'Aksesuar'],
                ['id' => 1503, 'name' => 'Çanta'],
            ]],
            ['id' => 16, 'name' => 'Hobi', 'icon' => '🎲', 'subCategories' => [
                ['id' => 1601, 'name' => 'Masa Oyunları'],
                ['id' => 1602, 'name' => 'Pul/Filateli'],
                ['id' => 1603, 'name' => 'Kartlar & Trading Cards'],
                ['id' => 1604, 'name' => 'Model Araç/Diecast'],
                ['id' => 1605, 'name' => 'Figür & Heykel'],
                ['id' => 1606, 'name' => 'Lego & Yapım Setleri'],
                ['id' => 1607, 'name' => 'Plak & Kaset'],
                ['id' => 1608, 'name' => 'Eski Para/Nümismatik'],
                ['id' => 1609, 'name' => 'Efemerat & Belge'],
                ['id' => 1610, 'name' => 'Maket & Kitler'],
            ]],
            ['id' => 17, 'name' => 'Efemer', 'icon' => '✉️', 'subCategories' => [
                ['id' => 1701, 'name' => 'Kartpostal'],
                ['id' => 1702, 'name' => 'Belge'],
                ['id' => 1703, 'name' => 'Fotoğraflar'],
            ]],
            ['id' => 18, 'name' => 'Mutfak', 'icon' => '🍳', 'subCategories' => [
                ['id' => 1801, 'name' => 'Porselen'],
                ['id' => 1802, 'name' => 'Bakır'],
                ['id' => 1803, 'name' => 'Cam Eşya'],
            ]],
            ['id' => 19, 'name' => 'Ofis', 'icon' => '✒️', 'subCategories' => [
                ['id' => 1901, 'name' => 'Dolma Kalem'],
                ['id' => 1902, 'name' => 'Daktilo'],
                ['id' => 1903, 'name' => 'Kırtasiye'],
            ]],
            ['id' => 20, 'name' => 'Bahçe', 'icon' => '🌻', 'subCategories' => [
                ['id' => 2001, 'name' => 'Bitkiler'],
                ['id' => 2002, 'name' => 'Mobilya'],
                ['id' => 2003, 'name' => 'Heykeller'],
            ]],
            ['id' => 21, 'name' => 'Diğer', 'icon' => '📦', 'subCategories' => [
                ['id' => 2101, 'name' => 'Çeşitli'],
                ['id' => 2102, 'name' => 'Karma Paketler'],
                ['id' => 2103, 'name' => 'Her Şey'],
            ]],
        ];
    }
}
