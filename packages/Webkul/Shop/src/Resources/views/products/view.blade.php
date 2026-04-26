@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = $reviewHelper->getAverageRating($product);

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);

    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')) && !$product->auction)
        <div class="flex  px-7 max-lg:hidden ">
            <x-shop::breadcrumbs
                name="product"
                :entity="$product"
            />
        </div>
    @endif

    <!-- Product Information Vue Component -->
    <v-product>
        <x-shop::shimmer.products.view />
    </v-product>

    <!-- Information Section -->
    <div class="1180:mt-20">
        <div class="max-1180:hidden">
            <x-shop::tabs
                position="center"
                ref="productTabs"
            >
                <!-- Description Tab -->
                {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                <x-shop::tabs.item
                    id="descritpion-tab"
                    class="container mt-[60px] !p-0"
                    :title="trans('shop::app.products.view.description')"
                    :is-selected="true"
                >
                    <div class="container mt-[60px] max-1180:px-5">
                        <p class="text-lg text-zinc-500 max-1180:text-sm">
                            {!! $product->description !!}
                        </p>
                    </div>
                </x-shop::tabs.item>

                {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

                <!-- Additional Information Tab -->
                @if(count($attributeData))
                    <x-shop::tabs.item
                        id="information-tab"
                        class="container mt-[60px] !p-0"
                        :title="trans('shop::app.products.view.additional-information')"
                        :is-selected="false"
                    >
                        <div class="container mt-[60px] max-1180:px-5">
                            <div class="mt-8 grid max-w-max grid-cols-[auto_1fr] gap-4">
                                @foreach ($customAttributeValues as $customAttributeValue)
                                    @if (! empty($customAttributeValue['value']))
                                        <div class="grid">
                                            <p class="text-base text-black">
                                                {!! $customAttributeValue['label'] !!}
                                            </p>
                                        </div>

                                        @if ($customAttributeValue['type'] == 'file')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <span class="icon-download text-2xl"></span>
                                            </a>
                                        @elseif ($customAttributeValue['type'] == 'image')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <img
                                                    class="h-5 min-h-5 w-5 min-w-5"
                                                    src="{{ Storage::url($customAttributeValue['value']) }}"
                                                />
                                            </a>
                                        @else
                                            <div class="grid">
                                                <p class="text-base text-zinc-500">
                                                    {!! $customAttributeValue['value'] !!}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </x-shop::tabs.item>
                @endif

                <!-- Reviews Tab -->
                <x-shop::tabs.item
                    id="review-tab"
                    class="container mt-[60px] !p-0"
                    :title="trans('shop::app.products.view.review')"
                    :is-selected="false"
                >
                    @include('shop::products.view.reviews')
                </x-shop::tabs.item>
            </x-shop::tabs>
        </div>
    </div>

    <!-- Information Section -->
    <div class="container mt-6 grid gap-3 !p-0 max-1180:px-5 1180:hidden">
        <!-- Description Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="true"
        >
            <x-slot:header class="bg-gray-100 max-md:!py-3 max-sm:!py-2">
                <p class="text-base font-medium 1180:hidden">
                    @lang('shop::app.products.view.description')
                </p>
            </x-slot>

            <x-slot:content class="max-sm:px-0">
                <div class="mb-5 text-lg text-zinc-500 max-1180:text-sm max-md:mb-1 max-md:px-4">
                    {!! $product->description !!}
                </div>
            </x-slot>
        </x-shop::accordion>

        <!-- Additional Information Accordion -->
        @if (count($attributeData))
            <x-shop::accordion
                class="max-md:border-none"
                :is-active="false"
            >
                <x-slot:header class="bg-gray-100 max-md:!py-3 max-sm:!py-2">
                    <p class="text-base font-medium 1180:hidden">
                        @lang('shop::app.products.view.additional-information')
                    </p>
                </x-slot>

                <x-slot:content class="max-sm:px-0">
                    <div class="container max-1180:px-5">
                        <div class="grid max-w-max grid-cols-[auto_1fr] gap-4 text-lg text-zinc-500 max-1180:text-sm">
                            @foreach ($customAttributeValues as $customAttributeValue)
                                @if (! empty($customAttributeValue['value']))
                                    <div class="grid">
                                        <p class="text-base text-black">
                                            {{ $customAttributeValue['label'] }}
                                        </p>
                                    </div>

                                    @if ($customAttributeValue['type'] == 'file')
                                        <a
                                            href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                            download="{{ $customAttributeValue['label'] }}"
                                        >
                                            <span class="icon-download text-2xl"></span>
                                        </a>
                                    @elseif ($customAttributeValue['type'] == 'image')
                                        <a
                                            href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                            download="{{ $customAttributeValue['label'] }}"
                                        >
                                            <img
                                                class="h-5 min-h-5 w-5 min-w-5"
                                                src="{{ Storage::url($customAttributeValue['value']) }}"
                                                alt="Product Image"
                                            />
                                        </a>
                                    @else
                                        <div class="grid">
                                            <p class="text-base text-zinc-500">
                                                {{ $customAttributeValue['value'] ?? '-' }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </x-slot>
            </x-shop::accordion>
        @endif

        <!-- Reviews Accordion -->
        <x-shop::accordion
            class="max-md:border-none"
            :is-active="false"
        >
            <x-slot:header
                class="bg-gray-100 max-md:!py-3 max-sm:!py-2"
                id="review-accordian-button"
            >
                <p class="text-base font-medium">
                    @lang('shop::app.products.view.review')
                </p>
            </x-slot>

            <x-slot:content>
                @include('shop::products.view.reviews')
            </x-slot>
        </x-shop::accordion>
    </div>

    <v-product-associations />

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-template"
        >


            <x-shop::form
                v-slot="{ handleSubmit }"
                as="div"
                @submit.prevent="() => {}"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, makeBid)"
                >

                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    <input
                        type="hidden"
                        name="is_buy_now"
                        v-model="is_buy_now"
                    >

                    <div class="container px-[60px] max-1180:px-0">
                        <div class="mt-12 flex gap-9 max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-4">
                            <!-- Gallery Blade Inclusion -->
                            @include('shop::products.view.gallery')

                            <!-- Details -->
                            <div class="relative max-w-[590px] max-1180:w-full max-1180:max-w-full max-1180:px-5 max-sm:px-4" style="min-width: 460px;">
                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <div class="flex justify-between gap-4 text-center align-middle" style="vertical-align: middle; align-items: center;
  ">
                                    <h1 class="break-words text-lg font-medium max-sm:text-lg" style=" vertical-align: middle;">
                                        {{ $product->name }}


                                    </h1>

                                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                        <div
                                            class=" flex max-h-[46px] min-h-[46px] min-w-[46px] cursor-pointer items-center justify-center   bg-white text-2xl transition-all hover:opacity-[0.8] max-sm:max-h-7 max-sm:min-h-7 max-sm:min-w-7 max-sm:text-base"
                                            role="button"
                                            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
                                            tabindex="0"
                                            :class="isWishlist ? 'icon-heart-fill text-red-600' : 'fa fa-eye'"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    @endif
                                </div>

                                @if($product->auction)
                                @php
                                    $auctionModel   = $product->auction;
                                    $auctionStarts  = optional($auctionModel->start_at)->toIso8601String();
                                    $auctionEnds    = optional($auctionModel->end_at)->toIso8601String();
                                    // Mezat durumunu STATUS kolonundan türetiyoruz (zamandan bağımsız):
                                    //   - live:     status=active                 → bitiş satırında küçük countdown (end_at)
                                    //   - upcoming: status in [pending,approved]  → büyük kutu start_at'e geri sayar
                                    //   - ended:    diğer durumlar veya end_at geçmiş → bitiş satırında tarih/"Sona erdi"
                                    $auctionStatus = $auctionModel->status;
                                    if ($auctionStatus === \App\Models\Auction::STATUS_ACTIVE) {
                                        $auctionState = 'live';
                                    } elseif (in_array($auctionStatus, [
                                        \App\Models\Auction::STATUS_PENDING,
                                        \App\Models\Auction::STATUS_APPROVED,
                                    ], true)) {
                                        $auctionState = 'upcoming';
                                    } else {
                                        $auctionState = 'ended';
                                    }
                                @endphp

                                <div id="countdownF"
                                     class="countdown-wrapper"
                                     data-starts-at="{{ $auctionStarts }}"
                                     data-ends-at="{{ $auctionEnds }}"
                                     data-state="{{ $auctionState }}"
                                     style="{{ $auctionState === 'upcoming' ? '' : 'display:none' }}">
                                    <div class="time-box">
                                        <span id="cd-days" class="time-number">0</span>
                                        <span class="time-label">Gün</span>
                                    </div>

                                    <div class="time-box">
                                        <span id="cd-hours" class="time-number">0</span>
                                        <span class="time-label">Saat</span>
                                    </div>

                                    <div class="time-box">
                                        <span id="cd-minutes" class="time-number">0</span>
                                        <span class="time-label">Dakika</span>
                                    </div>

                                    <div class="time-box">
                                        <span id="cd-seconds" class="time-number">0</span>
                                        <span class="time-label">Saniye</span>
                                    </div>
                                </div>


                                <div class="auction-box">

                                    <!-- ÜST BİLGİ -->
                                    <div class="auction-info">
                                        <div class="info-left">
                                            <div class="date-row">
                                                <span>Başlangıç :</span>
                                                <span>  {{ $auctionModel->start_at_formatted }}</span>
                                            </div>
                                            <div class="date-row">
                                                <span>Bitiş :</span>
                                                <span id="auctionEndDisplay"
                                                      data-ends-at="{{ $auctionEnds }}"
                                                      data-end-formatted="{{ $auctionModel->end_at_formatted }}"
                                                      data-state="{{ $auctionState }}">@if($auctionState === 'live')<span class="end-countdown is-live">--:--:--</span>@elseif($auctionState === 'ended')<span class="end-countdown is-expired">Sona erdi</span>@else{{ $auctionModel->end_at_formatted }}@endif</span>
                                            </div>

                                        </div>

                                        <div class="info-right" style="">
                                            <span class="status-badge">Bekliyor</span>
                                            <div class="start-price">
                                                Başlangıç: <strong>200 ₺</strong>
                                            </div>
                                        </div>
                                    </div>



                                </div>




                                {{--   {!! view_render_event('bagisto.shop.products.name.after', ['product' => $product]) !!} --}}

                                <!-- Rating -->
                                {{--   {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

                                @if ($totalRatings = $reviewHelper->getTotalFeedback($product))
                                    <!-- Scroll To Reviews Section and Activate Reviews Tab -->
                                    <div
                                        class="mt-1 w-max cursor-pointer max-sm:mt-1.5"
                                        role="button"
                                        tabindex="0"
                                        @click="scrollToReview"
                                    >
                                        <x-shop::products.ratings
                                            class="transition-all hover:border-gray-400 max-sm:px-3 max-sm:py-1"
                                            :average="$avgRatings"
                                            :total="$totalRatings"
                                            ::rating="true"
                                        />
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}

                                <!-- Pricing -->
                                {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                                <p class="mt-[22px] flex items-center gap-2.5 text-2xl !font-medium max-sm:mt-2 max-sm:gap-x-2.5 max-sm:gap-y-0 max-sm:text-lg">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                                </p>

                                @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                                    <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                        (@lang('shop::app.products.view.tax-inclusive'))
                                    </span>
                                @endif

                                @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                                    <div class="mt-2.5 grid gap-1.5">
                                        @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                                            <p class="text-zinc-500 [&>*]:text-black">
                                                {!! $offer !!}
                                            </p>
                                        @endforeach
                                    </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                                {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                                <p class="mt-6 text-lg text-zinc-500 max-sm:mt-1.5 max-sm:text-sm">
                                    {!! $product->short_description !!}
                                </p>
                                --}}



                                <div
                                    id="auctionStream"

                                ></div>


                                <div class="bid-bar">
                                    <!-- SOL -->
                                    <button type="button" class="buy-now">
                                        <span class="label">Hemen Al</span>
                                        &nbsp;
                                        <strong class="price">₺ 12.500</strong>
                                    </button>

                                    <!-- SAĞ -->
                                    <div class="bid-actions">

                                        <div class="bid-input">
                                            <button type="button" onclick="changeBid(-100)">−</button>

                                            <input type="number" id="bidAmount" value="1000" min="0">
                                            <span class="currency">₺</span>
                                            <button type="button" onclick="changeBid(100)">+</button>
                                        </div>

                                        <button type="button" class="bid-submit" @click="makeBid">
                                             Teklif Ver
                                        </button>
                                    </div>
                                </div>
                                @endif

                                {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                                @include('shop::products.view.types.simple')

                                @include('shop::products.view.types.configurable')

                                @include('shop::products.view.types.grouped')

                                @include('shop::products.view.types.bundle')

                                @include('shop::products.view.types.downloadable')

                                @include('shop::products.view.types.booking')

                                <!-- Product Actions and Quantity Box -->
                                <div class="mt-8 flex max-w-[470px] gap-4 max-sm:mt-4">


                                </div>

                                <!-- Buy Now Button -->
                                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                    {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                                    @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                        <x-shop::button
                                            type="submit"
                                            class="primary-button mt-5 w-full max-w-[470px] max-md:py-3 max-sm:mt-3 max-sm:rounded-lg max-sm:py-1.5"
                                            button-type="primary-button"
                                            :title="trans('shop::app.products.view.buy-now')"
                                            :disabled="! $product->isSaleable(1)"
                                            ::loading="isStoring.buyNow"
                                            @click="is_buy_now=1;"
                                            ::disabled="isStoring.buyNow"
                                        />
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.before', ['product' => $product]) !!}

                                <!-- Share Buttons -->
                                <div class="mt-10 flex gap-9 max-md:mt-4 max-md:flex-wrap max-sm:justify-center max-sm:gap-3">
                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                    <div
                                        class="flex cursor-pointer items-center justify-center gap-2.5 max-sm:gap-1.5 max-sm:text-base"
                                        role="button"
                                        tabindex="0"
                                        @click="is_buy_now=0; addToCompare({{ $product->id }})"
                                    >
                                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                                            <span
                                                class="fa fa-eye "
                                                style="font-size: 12px;"
                                            ></span>

                                            @lang('shop::app.products.view.compare')
                                        @endif
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>

                                {!! view_render_event('bagisto.shop.products.view.additional_actions.after', ['product' => $product]) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </x-shop::form>
        </script>


        @push('scripts')


            <script>
                // Mezat countdown — #auctionEndDisplay / #countdownF, v-product'ın
                // <script type="text/x-template"> içinde; Vue DOM'a basana kadar id'ler yok, bu yüzden
                // RAF + MutationObserver ile gecikmeli başlatıyoruz.
                (function () {
                    let started = false;

                    const parseIso = (v) => {
                        if (!v) return null;
                        const t = new Date(v).getTime();
                        return Number.isFinite(t) ? t : null;
                    };

                    const pad = (n) => String(n).padStart(2, '0');

                    const formatDiff = (diffMs) => {
                        if (diffMs <= 0) return null;
                        const days    = Math.floor(diffMs / 86400000);
                        const hours   = Math.floor((diffMs % 86400000) / 3600000);
                        const minutes = Math.floor((diffMs % 3600000) / 60000);
                        const seconds = Math.floor((diffMs % 60000) / 1000);
                        return { days, hours, minutes, seconds };
                    };

                    function startCountdown() {
                        if (started) {
                            return true;
                        }
                        const bigBox    = document.getElementById('countdownF');
                        const daysEl    = document.getElementById('cd-days');
                        const hoursEl   = document.getElementById('cd-hours');
                        const minutesEl = document.getElementById('cd-minutes');
                        const secondsEl = document.getElementById('cd-seconds');
                        const endWrap   = document.getElementById('auctionEndDisplay');

                        if (!bigBox && !endWrap) {
                            return false;
                        }
                        started = true;

                        const state    = endWrap?.dataset.state ?? bigBox?.dataset.state ?? 'ended';
                        const startsAt = parseIso(bigBox?.dataset.startsAt);
                        const endsAt   = parseIso(endWrap?.dataset.endsAt ?? bigBox?.dataset.endsAt);

                        const renderLive = () => {
                            if (!endsAt) {
                                return;
                            }
                            const diff = formatDiff(endsAt - Date.now());
                            const node = endWrap?.querySelector('.end-countdown');
                            if (!diff) {
                                if (node) {
                                    node.className = 'end-countdown is-expired';
                                    node.textContent = 'Sona erdi';
                                }
                                return;
                            }
                            const label = diff.days > 0
                                ? `${diff.days}g ${pad(diff.hours)}:${pad(diff.minutes)}:${pad(diff.seconds)}`
                                : `${pad(diff.hours)}:${pad(diff.minutes)}:${pad(diff.seconds)}`;
                            if (node) {
                                node.textContent = label;
                            }
                        };

                        const renderUpcoming = () => {
                            if (!startsAt) {
                                return;
                            }
                            const diff = formatDiff(startsAt - Date.now());
                            if (!diff) {
                                if (bigBox) {
                                    bigBox.style.display = 'none';
                                }
                                return;
                            }
                            if (daysEl) {
                                daysEl.textContent = diff.days;
                            }
                            if (hoursEl) {
                                hoursEl.textContent = diff.hours;
                            }
                            if (minutesEl) {
                                minutesEl.textContent = diff.minutes;
                            }
                            if (secondsEl) {
                                secondsEl.textContent = diff.seconds;
                            }
                        };

                        const tick = () => {
                            if (state === 'live') {
                                return renderLive();
                            }
                            if (state === 'upcoming') {
                                return renderUpcoming();
                            }
                        };

                        tick();
                        if (state === 'live' || state === 'upcoming') {
                            setInterval(tick, 1000);
                        }
                        return true;
                    }

                    let frames = 0;
                    function tryMount() {
                        if (startCountdown()) {
                            if (obs) {
                                obs.disconnect();
                            }
                            return;
                        }
                        if (++frames > 500) {
                            if (obs) {
                                obs.disconnect();
                            }
                            return;
                        }
                        requestAnimationFrame(tryMount);
                    }

                    const obs = typeof MutationObserver !== 'undefined'
                        ? new MutationObserver(() => {
                            if (document.getElementById('auctionEndDisplay') || document.getElementById('countdownF')) {
                                if (startCountdown()) {
                                    if (obs) {
                                        obs.disconnect();
                                    }
                                }
                            }
                        })
                        : null;
                    if (obs) {
                        obs.observe(document.body, { childList: true, subtree: true });
                    }
                    tryMount();
                })();


                    function changeBid(amount) {
                    const input = document.getElementById('bidAmount');
                    let value = parseInt(input.value) || 0;
                    value = Math.max(0, value + amount);
                    input.value = value;
                }


            </script>
        @endpush

    <style>
        .countdown-wrapper {
            display: flex;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            text-align: center;
            align-items: center;
            justify-content: center;
            margin: 2px 0 10px 0;
        }

        .time-box {
            min-width: 70px;
            padding: 6px 5px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .time-number {
            font-size: 20px;
            font-weight: 600;
            line-height: 1.2;
        }

        .time-label {
            font-size: 11px;
            text-transform: uppercase;
            opacity: 0.6;
            margin-top: 2px;
        }

        .end-countdown {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 13px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.3px;
        }

        .end-countdown.is-live {
            background: #fff1f0;
            color: #c92a2a;
        }

        .end-countdown.is-expired {
            background: #f1f3f5;
            color: #6c757d;
        }
        .bid-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0px;
            padding: 0px;
            border-radius: 4px;
            background: #fff;

        }

        /* SOL */
        .buy-now {
            display: flex;

            padding: 10px 12px;
            background: #5fc17b;
            color: #fff !important;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            align-items: center;
        }

        .buy-now .label {
            font-size: 12px;
            opacity: .8;
            color: #fff !important;
        }

        .buy-now .price {
            font-size: 14px;
            font-weight: 700;
            color: #fff !important;
        }

        /* SAĞ */
        .bid-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .currency {

            border-radius: 4px;
            padding: 0 10px;
            align-items: center;
            justify-content: center;

        }

        .bid-input {
            display: flex;
            align-items: center;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .bid-input button {
            width: 36px;
            height: 42px;
            border: none;
            background: #f5f5f5;
            cursor: pointer;
            font-size: 18px;
        }

        .bid-input input {
            width: 80px;
            height: 42px;
            border: none;
            text-align: center;
            font-weight: 600;
        }

        .bid-submit {
            height: 44px;
            padding: 0 16px;
            border-radius: 4px;
            border: none;
            background: #ff9800;
            color: #fff;

            cursor: pointer;
        }

        .auction-box {

            border-radius: 4px;
            padding: 4px;
            background: #fff;
            max-width: 700px;
        }

        .auction-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            font-size: 12px;
            opacity: .8;

        }

        .info-left{}
        .info-right{
            text-align: right;
        }

        .date-row {
            font-size: 14px;
            color: #374151;
        }

        .start-price {
            margin-top: 6px;
            font-size: 14px;
        }

        .status-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }


        .auction-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }


        #auctionStream{
            max-height: 250px;
            overflow-y: auto;
            min-height: 250px;
            text-align: right;
            font-size: 14px;
            opacity: 0.8;
            color: #ccc;
        }

        #auctionStream span{
            opacity: 1;

        }


    </style>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: Boolean("{{ (boolean) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"),

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        customerName: @json(auth()->guard('customer')->user()?->name ?? 'Misafir'),

                        is_buy_now: 0,

                        isStoring: {
                            addToCart: false,

                            buyNow: false,
                        },
                    }
                },

                methods: {
                    makeBid(params) {
                        if (!window.auctionSocket || window.auctionSocket.readyState !== WebSocket.OPEN) {
                            console.warn("Socket bağlı değil");
                            return;
                        }

                        const bidAmount = document.getElementById('bidAmount').value || 0;

                        window.auctionSocket.send(JSON.stringify({
                            user: this.customerName,
                            message: parseInt(bidAmount)
                        }));

                        const stream = document.getElementById('auctionStream');
                        stream.scrollTop = stream.scrollHeight;

                    },

                    addToCart(params) {
                        const operation = this.is_buy_now ? 'buyNow' : 'addToCart';

                        this.isStoring[operation] = true;

                        let formData = new FormData(this.$refs.formData);

                        this.ensureQuantity(formData);

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    if (response.data.redirect) {
                                        window.location.href= response.data.redirect;
                                    }
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isStoring[operation] = false;
                            })
                            .catch(error => {
                                this.isStoring[operation] = false;

                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            });
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                    product_id: "{{ $product->id }}"
                                })
                                .then(response => {
                                    this.isWishlist = ! this.isWishlist;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
                        }
                    },

                    addToCompare(productId) {
                        /**
                         * This will handle for customers.
                         */
                        if (this.isCustomer) {
                            this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                    'product_id': productId
                                })
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                        return;
                                    }

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                });

                            return;
                        }

                        /**
                         * This will handle for guests.
                         */
                        let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                        if (existingItems.length) {
                            if (! existingItems.includes(productId)) {
                                existingItems.push(productId);

                                this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.products.view.already-in-compare')" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                        }
                    },

                    updateQty(quantity, id) {
                        this.isLoading = true;

                        let qty = {};

                        qty[id] = quantity;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                            .then(response => {
                                if (response.data.message) {
                                    this.cart = response.data.data;
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isLoading = false;
                            }).catch(error => this.isLoading = false);
                    },

                    getCompareItemsStorageKey() {
                        return 'compare_items';
                    },

                    setStorageValue(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },

                    scrollToReview() {
                        let accordianElement = document.querySelector('#review-accordian-button');

                        if (accordianElement) {
                            accordianElement.click();

                            accordianElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }

                        let tabElement = document.querySelector('#review-tab-button');

                        if (tabElement) {
                            tabElement.click();

                            tabElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    },

                    ensureQuantity(formData) {
                        if (! formData.has('quantity')) {
                            formData.append('quantity', 1);
                        }
                    },
                },
            });
        </script>

        <script
            type="text/x-template"
            id="v-product-associations-template"
        >
            <div ref="carouselWrapper">
                <template v-if="isVisible">
                    <!-- Featured Products -->
                    <x-shop::products.carousel
                        :title="trans('shop::app.products.view.related-product-title')"
                        :src="route('shop.api.products.related.index', ['id' => $product->id])"
                    />

                    <!-- Up-sell Products -->
                    <x-shop::products.carousel
                        :title="trans('shop::app.products.view.up-sell-title')"
                        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
                    />
                </template>
            </div>
        </script>

        <script type="module">
            app.component('v-product-associations', {
                template: '#v-product-associations-template',

                data() {
                    return {
                        isVisible: false,
                    };
                },

                mounted() {
                    const observer = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    this.isVisible = true;
                                    observer.unobserve(entry.target); // Stop observing
                                }
                            });
                        },
                        { threshold: 0.1 }
                    );

                    observer.observe(this.$refs.carouselWrapper);
                }
            });
        </script>

        <script>
            (function () {
                // Guard: prevent duplicate connections
                if (window.auctionSocket && window.auctionSocket.readyState === WebSocket.OPEN) {
                    console.log("[WS] Already connected, skipping...");
                    return;
                }

                window.auctionSocket = null;

                async function connectAuctionSocket(auctionId) {
                    try {
                        var token = 'test-token-122';
                        window.auctionSocket = new WebSocket("ws://localhost:8081/ws?token=" + token);

                        window.auctionSocket.onopen = function () {
                            console.log("[WS] Connected to auction " + auctionId);
                        };

                        window.auctionSocket.onmessage = function (event) {
                            const data = JSON.parse(event.data);

                            // Skip system messages (welcome etc.)
                            if (data.type === 'system') {
                                console.log("[WS] System:", data.message);
                                return;
                            }

                            const stream = document.getElementById('auctionStream');
                            if (stream) {
                                stream.innerHTML +=
                                    `<p><span>${data.user}</span> kullannıcısı teklif verdi :
                                    <strong>${data.message}₺</strong></p>`;
                                stream.scrollTop = stream.scrollHeight;
                            }
                        };

                        window.auctionSocket.onclose = () =>
                            console.log("[WS] Connection closed");

                        window.auctionSocket.onerror = err =>
                            console.error("[WS] Error", err);

                    } catch (err) {
                        console.error("[WS] Failed to connect", err);
                    }
                }

                document.addEventListener("DOMContentLoaded", function () {
                    var AUCTION_ID = 32652;
                    connectAuctionSocket(AUCTION_ID);
                });

            })();
        </script>
    @endPushOnce
</x-shop::layouts>
