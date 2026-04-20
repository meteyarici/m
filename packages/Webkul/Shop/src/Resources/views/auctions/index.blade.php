<x-shop::layouts>
    <x-slot:title>Mezatlar</x-slot>

    <div class="container mt-8 max-1180:px-5 max-md:mt-6 max-md:px-4">

        <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900">Mezatlar</h1>
                <p class="text-sm text-zinc-500">Canlı teklif verin, favori ürünleri kaçırmayın.</p>
            </div>

            <div class="inline-flex rounded-lg border border-zinc-200 bg-white p-1 text-sm">
                @foreach (['active' => 'Canlı', 'upcoming' => 'Yakında', 'closed' => 'Bitti'] as $key => $label)
                    <a href="{{ route('shop.auctions.index', ['filter' => $key]) }}"
                       class="px-4 py-1.5 rounded-md transition
                            {{ $filter === $key ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        @if ($auctions->isEmpty())
            <div class="rounded-xl border border-dashed border-zinc-300 bg-white p-12 text-center">
                <p class="text-zinc-500">
                    @if ($filter === 'active') Şu anda canlı mezat yok.
                    @elseif ($filter === 'upcoming') Yaklaşan mezat yok.
                    @else Sonlanmış mezat kaydı yok.
                    @endif
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($auctions as $auction)
                    <a href="{{ route('shop.auctions.show', $auction->id) }}"
                       class="group rounded-xl border border-zinc-200 bg-white overflow-hidden hover:shadow-md transition">
                        <div class="aspect-video bg-zinc-100 flex items-center justify-center overflow-hidden">
                            @if ($auction->image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($auction->image) }}"
                                     alt="{{ $auction->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            @else
                                <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V10a2 2 0 012-2z"/>
                                </svg>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[11px] px-2 py-0.5 rounded-full
                                    @if($auction->status === 'active') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif(in_array($auction->status, ['closed','cancelled','rejected'])) bg-zinc-100 text-zinc-600 border border-zinc-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ ucfirst($auction->status) }}
                                </span>
                                <span class="text-[11px] text-zinc-400">Bitiş: {{ $auction->end_at_formatted }}</span>
                            </div>

                            <h3 class="font-semibold text-zinc-900 truncate">{{ $auction->title ?? ('Mezat #'.$auction->id) }}</h3>

                            <div class="mt-2 flex items-baseline justify-between">
                                <div>
                                    <div class="text-[11px] text-zinc-500">Mevcut Fiyat</div>
                                    <div class="font-mono font-bold text-zinc-900">{{ number_format($auction->current_price, 2, ',', '.') }} ₺</div>
                                </div>

                                @if ($auction->is_buy_now_available && $auction->buy_now_price > 0)
                                    <div class="text-right">
                                        <div class="text-[11px] text-zinc-500">Hemen Al</div>
                                        <div class="font-medium text-zinc-700">{{ number_format($auction->buy_now_price, 2, ',', '.') }} ₺</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $auctions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-shop::layouts>
