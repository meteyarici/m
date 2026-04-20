<v-collections-carrousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
</v-collections-carrousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-collections-carrousel-template"
    >
        <div
            class="relative group mt-4 max-lg:px-8 max-md:mt-8 max-sm:mt-7 max-sm:!px-4"
            v-if="! isLoading"
        >
            <div
                ref="swiperContainer"
                class="flex gap-1 pb-2.5 mt-4 overflow-auto scroll-smooth scrollbar-hide max-md:gap-7 max-md:mt-5 max-sm:gap-4 max-md:pb-0"
            >
                <div
                    v-for="(product, index) in products"
                    :key="index"
                    class="flex-none"
                    style="width: 960px; min-width: 960px;"
                >
                    <img src="/assets/images/slider/2.png" class="w-full" style="height: 300px; border-radius: 8px; object-fit: cover;">

                </div>


            </div>

            <div
                class="absolute left-10 top-1/2 -translate-y-1/2 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/50 hover:bg-white rounded-full p-2 cursor-pointer shadow-md"
                @click="swipeLeft"
                role="button"
            >
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div
                class="absolute right-10 top-1/2 -translate-y-1/2 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/50 hover:bg-white rounded-full p-2 cursor-pointer shadow-md"
                @click="swipeRight"
                role="button"
            >
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <!-- Product Card Listing -->
        <template v-if="isLoading">
            <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
        </template>
    </script>

    <style>
        .right-10{
            right: 10px;
            background: rgba(255,255,255, .5);
        }

        .left-10{
            left: 10px;
            background: rgba(255,255,255, .5);
        }

    </style>
    <script type="module">
        app.component('v-collections-carrousel', {
            template: '#v-collections-carrousel-template',

            props: [
                'src',
                'title',
                'navigationLink',
            ],

            data() {
                return {
                    isLoading: true,

                    products: [],

                    offset: 965,

                    isScreenMax2xl: window.innerWidth <= 1440,
                };
            },

            mounted() {
                this.getProducts();
            },

            created() {
                window.addEventListener('resize', this.updateScreenSize);
            },

            beforeDestroy() {
                window.removeEventListener('resize', this.updateScreenSize);
            },

            methods: {
                getProducts() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;

                            this.products = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                updateScreenSize() {
                    this.isScreenMax2xl = window.innerWidth <= 1440;
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;

                    // Check if scroll reaches the end
                    if (container.scrollLeft + container.clientWidth >= container.scrollWidth) {
                        // Reset scroll to the beginning
                        container.scrollLeft = 0;
                    } else {
                        // Scroll to the right
                        container.scrollLeft += this.offset;
                    }
                },
            },
        });
    </script>
@endPushOnce
