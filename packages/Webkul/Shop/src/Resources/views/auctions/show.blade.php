<x-shop::layouts>
    <x-slot:title>
        {{ $auction->title ?? ('Mezat #'.$auction->id) }}
    </x-slot>

    <div class="container mt-8 max-1180:px-5 max-md:mt-6 max-md:px-4">

        <nav class="text-xs text-zinc-500 mb-4">
            <a href="{{ route('shop.home.index') }}" class="hover:text-zinc-800">Ana Sayfa</a>
            <span class="mx-1">/</span>
            <a href="{{ route('shop.auctions.index') }}" class="hover:text-zinc-800">Mezatlar</a>
            <span class="mx-1">/</span>
            <span class="text-zinc-700">{{ $auction->title ?? ('Mezat #'.$auction->id) }}</span>
        </nav>

        <div id="auction-detail"
             class="grid grid-cols-1 lg:grid-cols-3 gap-6"
             data-auction-id="{{ $auction->id }}"
             data-ws-endpoint="{{ $wsEndpoint }}"
             data-ws-token-url="{{ route('shop.auctions.ws-token', $auction->id) }}"
             data-bid-url="{{ route('shop.api.auction.bid.make') }}"
             data-end-at="{{ optional($auction->end_at)->toIso8601String() }}"
             data-start-at="{{ optional($auction->start_at)->toIso8601String() }}"
             data-status="{{ $auction->status }}">

            <div class="lg:col-span-2 rounded-xl border border-zinc-200 bg-white p-6 max-md:p-4">
                <div class="aspect-video w-full rounded-lg overflow-hidden bg-zinc-100 flex items-center justify-center mb-4">
                    @if ($auction->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($auction->image) }}"
                             alt="{{ $auction->title }}"
                             class="w-full h-full object-contain">
                    @else
                        <svg class="w-16 h-16 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V10a2 2 0 012-2z"/>
                        </svg>
                    @endif
                </div>

                <h1 class="text-2xl font-semibold text-zinc-900 mb-2">
                    {{ $auction->title ?? ('Mezat #'.$auction->id) }}
                </h1>

                <div class="flex flex-wrap items-center gap-2 mb-4 text-xs">
                    <span class="px-2 py-1 rounded-full
                        @if($auction->status === 'active') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif(in_array($auction->status, ['closed','cancelled','rejected'])) bg-zinc-100 text-zinc-600 border border-zinc-200
                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                        {{ ucfirst($auction->status) }}
                    </span>
                    <span class="text-zinc-500">Başlangıç: {{ $auction->start_at_formatted }}</span>
                    <span class="text-zinc-500">Bitiş: {{ $auction->end_at_formatted }}</span>
                </div>

                @if ($auction->description)
                    <div class="prose prose-sm max-w-none text-zinc-700 mb-6">
                        {!! nl2br(e($auction->description)) !!}
                    </div>
                @endif

                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-zinc-900 mb-3">Canlı Teklif Akışı</h3>
                    <ul id="bid-feed" class="divide-y divide-zinc-100 rounded-lg border border-zinc-200 bg-white">
                        @forelse ($auction->bids as $bid)
                            <li class="flex items-center justify-between px-4 py-2 text-sm">
                                <span class="text-zinc-600">Müşteri #{{ $bid->customer_id }}</span>
                                <span class="font-mono font-semibold text-zinc-900">{{ number_format($bid->amount, 2, ',', '.') }} ₺</span>
                                <span class="text-xs text-zinc-400">{{ $bid->created_at?->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-center text-sm text-zinc-400">Henüz teklif yok. İlk teklifi siz verin!</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <aside class="rounded-xl border border-zinc-200 bg-white p-6 max-md:p-4 h-fit lg:sticky lg:top-6">
                <div class="text-xs text-zinc-500 mb-1">Mevcut Fiyat</div>
                <div class="flex items-baseline gap-2 mb-4">
                    <span id="current-price" class="text-3xl font-bold text-zinc-900">
                        {{ number_format($livePrice, 2, ',', '.') }}
                    </span>
                    <span class="text-lg text-zinc-500">₺</span>
                </div>

                <div class="rounded-lg bg-zinc-50 border border-zinc-200 p-3 mb-4">
                    <div class="text-xs text-zinc-500 mb-1">Bitişe Kalan</div>
                    <div id="countdown" class="text-xl font-mono font-semibold text-zinc-900">--:--:--</div>
                </div>

                <div class="space-y-1 text-sm text-zinc-600 mb-4">
                    <div class="flex justify-between">
                        <span>Başlangıç Fiyatı</span>
                        <span class="font-medium text-zinc-900">{{ number_format($auction->start_price, 2, ',', '.') }} ₺</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Min. Artış</span>
                        <span class="font-medium text-zinc-900">{{ number_format($auction->min_increment, 2, ',', '.') }} ₺</span>
                    </div>
                    @if ($auction->is_buy_now_available && $auction->buy_now_price > 0)
                        <div class="flex justify-between">
                            <span>Hemen Al</span>
                            <span class="font-medium text-zinc-900">{{ number_format($auction->buy_now_price, 2, ',', '.') }} ₺</span>
                        </div>
                    @endif
                </div>

                @auth('customer')
                    <form id="bid-form" class="space-y-2">
                        <label class="block text-xs font-medium text-zinc-700">Teklifiniz (₺)</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               id="bid-amount"
                               name="amount"
                               class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                               placeholder="{{ number_format($livePrice + ($auction->min_increment ?? 1), 2, '.', '') }}"
                               required>
                        <button type="submit"
                                id="bid-submit"
                                class="w-full rounded-lg bg-zinc-900 text-white font-semibold py-2.5 hover:bg-zinc-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Teklif Ver
                        </button>
                        <div id="bid-message" class="text-xs mt-2"></div>
                    </form>
                @else
                    <a href="{{ route('shop.customer.session.index') }}"
                       class="block w-full text-center rounded-lg bg-zinc-900 text-white font-semibold py-2.5 hover:bg-zinc-800 transition">
                        Teklif vermek için giriş yap
                    </a>
                @endauth

                <div class="mt-4 flex items-center gap-2 text-xs text-zinc-500">
                    <span id="ws-indicator" class="inline-block w-2 h-2 rounded-full bg-zinc-300"></span>
                    <span id="ws-status">Canlı bağlantı kuruluyor…</span>
                </div>
            </aside>
        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const root = document.getElementById('auction-detail');
        if (!root) return;

        const auctionId    = root.dataset.auctionId;
        const wsEndpoint   = root.dataset.wsEndpoint;
        const wsTokenUrl   = root.dataset.wsTokenUrl;
        const bidUrl       = root.dataset.bidUrl;
        const endAt        = root.dataset.endAt ? new Date(root.dataset.endAt) : null;
        const priceEl      = document.getElementById('current-price');
        const countdownEl  = document.getElementById('countdown');
        const feedEl       = document.getElementById('bid-feed');
        const form         = document.getElementById('bid-form');
        const messageEl    = document.getElementById('bid-message');
        const wsIndicator  = document.getElementById('ws-indicator');
        const wsStatusText = document.getElementById('ws-status');

        const formatTry = (v) => Number(v).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        function updateCountdown() {
            if (!endAt) return;
            const diff = endAt - new Date();
            if (diff <= 0) {
                countdownEl.textContent = 'Sona erdi';
                const submit = document.getElementById('bid-submit');
                if (submit) submit.disabled = true;
                return;
            }
            const h = Math.floor(diff / 3_600_000);
            const m = Math.floor((diff % 3_600_000) / 60_000);
            const s = Math.floor((diff % 60_000) / 1000);
            countdownEl.textContent = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);

        function setWsStatus(state) {
            wsIndicator.className = 'inline-block w-2 h-2 rounded-full ' + (
                state === 'online' ? 'bg-emerald-500' :
                state === 'error'  ? 'bg-red-500' : 'bg-zinc-300'
            );
            wsStatusText.textContent =
                state === 'online' ? 'Canlı bağlantı aktif' :
                state === 'error'  ? 'Canlı bağlantı hatası' : 'Canlı bağlantı kuruluyor…';
        }

        function appendBid({ customer_id, amount, ts }) {
            priceEl.textContent = formatTry(amount);
            const empty = feedEl.querySelector('.text-center');
            if (empty) empty.remove();
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between px-4 py-2 text-sm animate-[pulse_0.5s_ease-in-out]';
            li.innerHTML =
                `<span class="text-zinc-600">Müşteri #${customer_id}</span>` +
                `<span class="font-mono font-semibold text-zinc-900">${formatTry(amount)} ₺</span>` +
                `<span class="text-xs text-zinc-400">az önce</span>`;
            feedEl.insertBefore(li, feedEl.firstChild);
            while (feedEl.children.length > 10) feedEl.removeChild(feedEl.lastChild);
        }

        async function connectWs() {
            if (!wsEndpoint) return;

            try {
                const tokenRes = await fetch(wsTokenUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                });

                if (!tokenRes.ok) {
                    setWsStatus('error');
                    return;
                }
                const { token } = await tokenRes.json();

                const url = wsEndpoint + (wsEndpoint.includes('?') ? '&' : '?') + 'token=' + encodeURIComponent(token);
                const ws = new WebSocket(url);

                ws.onopen  = () => setWsStatus('online');
                ws.onerror = () => setWsStatus('error');
                ws.onclose = () => setWsStatus('error');
                ws.onmessage = (ev) => {
                    try {
                        const msg = JSON.parse(ev.data);
                        if (msg.type === 'bid' && String(msg.auction_id) === String(auctionId)) {
                            appendBid(msg);
                        }
                    } catch (_) {}
                };
            } catch (e) {
                setWsStatus('error');
            }
        }
        connectWs();

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                messageEl.textContent = '';
                messageEl.className = 'text-xs mt-2';

                const amount = parseFloat(document.getElementById('bid-amount').value);
                const submit = document.getElementById('bid-submit');
                submit.disabled = true;

                try {
                    const res = await fetch(bidUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ auction_id: Number(auctionId), amount }),
                    });

                    const body = await res.json();
                    messageEl.textContent = body.message || '';
                    messageEl.className = 'text-xs mt-2 ' + (body.success ? 'text-emerald-600' : 'text-red-600');

                    if (body.success && body.current_price) {
                        priceEl.textContent = formatTry(body.current_price);
                    }
                } catch (err) {
                    messageEl.textContent = 'İstek gönderilemedi.';
                    messageEl.className = 'text-xs mt-2 text-red-600';
                } finally {
                    submit.disabled = false;
                }
            });
        }
    })();
    </script>
    @endpush
</x-shop::layouts>
