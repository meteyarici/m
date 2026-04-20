<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
        class="product-card-main "
    >
        <!-- Grid Card -->
        <div
            class="group w-full rounded-lg border border-gray-100 bg-white p-2 transition-all duration-300 hover:shadow-md product-card-main "
            v-if="mode != 'list'"
        >
            <div class="relative overflow-hidden rounded-md">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a
                    :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                    :aria-label="product.name"
                    class="block overflow-hidden"
                >
                    <x-shop::media.images.lazy
                        class="relative w-full transition-transform duration-500 group-hover:scale-105"
                        ::src="product.base_image.medium_image_url"
                        ::srcset="`${product.base_image.small_image_url} 150w, ${product.base_image.medium_image_url} 300w`"
                        sizes="(max-width: 768px) 150px, 300px"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <div class="action-items">
                    <p
                        class="absolute top-1.5 inline-block rounded-sm bg-red-600 px-2.5 text-sm text-white max-sm:rounded-l-none max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs ltr:left-1.5 max-sm:ltr:left-0 rtl:right-5 max-sm:rtl:right-0"
                        v-if="product.on_sale"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                    <p
                        class="absolute top-1.5 inline-block rounded-sm bg-navyBlue px-2.5 text-sm text-white max-sm:rounded-l-none max-sm:rounded-r-xl max-sm:px-2 max-sm:py-0.5 max-sm:text-xs ltr:left-1.5 max-sm:ltr:left-0 rtl:right-1.5 max-sm:rtl:right-0"
                        v-else-if="product.is_new"
                    >
                        @lang('shop::app.components.products.card.new')
                    </p>
                </div>

                <div class="absolute top-2 right-2 opacity-0 transition-opacity duration-300 group-hover:opacity-100 max-md:opacity-100">
                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-zinc-200 bg-white shadow-sm hover:text-red-500"
                            @click="addToWishlist()"
                        >
                            <span :class="product.is_wishlist ? 'icon-heart-fill text-red-500' : 'icon-heart'"></span>
                        </button>
                    @endif
                </div>

                <div class="absolute bottom-2 left-2">
                    <x-shop::products.ratings
                        class="rounded bg-white/80 px-1.5 py-0.5 text-[10px] backdrop-blur-sm"
                        ::average="product.ratings.average"
                        ::total="product.ratings.total || product.reviews.total"
                        ::rating="false"
                        v-if="product.ratings.total || product.reviews.total"
                    />
                </div>
            </div>

            <div class="mt-3 flex flex-col gap-1 ">

                <p class="text-base text-sm  pname     "  id="product-title"
                   >
                    @{{ product.name }}
                </p>


                <div class="flex items-center justify-between gap-1  border-gray-50 pt-2">
                    <div class="text-base font-bold text-zinc-900" v-html="product.price_html"></div>
                    <div class="text-[10px] text-sm text-zinc-500 opacity-80" style="color: rgb(41 174 7);">
                        179g 09:07:31
                    </div>
                </div>

                <div class="mt-2 space-y-2">
                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                        <div class="flex items-center gap-2">
                            <button
                                class="w-full rounded bg-amber-500 text-white py-2 text-xs font-semibold transition-colors hover:bg-amber-500 disabled:opacity-50 tbutton"

                            >
                                İncele
                            </button>

                            <button
                                class="flex fbutton shrink-0 items-center justify-center rounded text-white transition-colors hover:bg-amber-500 disabled:opacity-50 hover:text-white "
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-1 flex items-center justify-between text-xs text-zinc-500 cursor-pointer ">
                            <div class="flex items-center gap-1.5">
                                <span class="flex h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>4 Teklif</span>
                            </div>

                            <div class="flex items-center  hover:text-zinc-700 gap-1.5">
                                <span class="flex h-1.5 w-1.5 rounded-full bg-green-600 animate-pulse"></span>
                                <span> 116 İnceleme</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </script>
    <style>
        .product-card-main {
            padding-bottom: 5px !important;
        }



        .bg-green-600{
            background-color: #479932;
            border:1px solid #3d3d3d;
        }

        .tbutton{
            transition: all 0.3s ease;
        }
        .tbutton:hover{
            background-color: rgb(255 144 0);
        }


        .fbutton {
            width: 40px;
            height: 30px;
            background: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .fbutton:hover {
            color: #fff;
            background-color: rgb(245 158 11);
        }

        .fbutton:hover svg {
            stroke: #ffffff;
        }

        .fbutton:hover svg path {
            stroke: #ffffff;
        }

        .final-price{
            color: darkcyan;
        }
        .pname{
            min-height: 2lh;
        }

        svg[role="button"] {
            outline: none; /* Varsayılan mavi çerçeveyi kaldırır */
            transition: all 0.3s ease; /* Geçişleri yumuşatır */
            cursor: pointer; /* Üzerine gelince el işareti çıkar */
        }

        svg[role="button"]:hover,
        svg[role="button"]:focus {
            color: #FF8C00; /* Koyu Turuncu renk */
            transform: scale(1.1); /* Hafifçe büyüyerek tepki verir (isteğe bağlı) */
        }
        svg[role="button"]:focus {
            /* Mavi yerine senin markana uygun bir renk (Örn: Turuncu) */
            color: #FFD700;
            /* İsteğe bağlı: Hafif bir dış parlama */
            filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
        }
        svg[role="button"]:active {
            transform: scale(0.95); /* Tıklayınca hafifçe içe çöker (basılma hissi) */
        }
    </style>
    <script>
        document.querySelectorAll('.pname').forEach(el => {
            // Eğer metin kutusunun gerçek yüksekliği (scrollHeight),
            // görünen yüksekliğinden (40px) büyükse fontu küçült
            if (el.scrollHeight > 30) {
                el.classList.replace('text-base', 'text-xs');
                el.classList.add('leading-4'); // Font küçülünce satır aralığını da daraltıyoruz
            }
        });
    </script>
    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,
                }
            },

            mounted() {
                // Vue DOM'u oluşturduktan hemen sonra çalışır
                this.$nextTick(() => {

                });
            },
            computed: {
                // Ürün ismindeki boşlukları temizleyen fonksiyon
                cleanedProductName() {
                    if (!this.product.name) return '';

                    // Hem başındaki-sonundaki boşlukları siler
                    // hem de varsa içindeki fazla satır başlarını (newline) temizler
                    return this.product.name.trim().replace(/\s+/g, ' ');
                }
            },

            methods: {
                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;

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
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }

                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endpushOnce
