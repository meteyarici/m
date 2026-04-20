
{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<v-topbar>
    <!-- Shimmer Effect -->
    <div class="flex items-center justify-between border border-b border-l-0 border-r-0 border-t-0 px-4">


        <!-- Offers -->
        <div
            class="shimmer h-6 w-72 rounded py-3"
            role="presentation"
        >
        </div>

        <!-- Currencies -->
        <div class="flex w-20 items-center justify-between gap-2.5 py-3">
            <div
                class="shimmer h-6 w-12 rounded"
                role="presentation"
            >
            </div>

            <div
                class="shimmer h-6 w-6 rounded"
                role="presentation"
            >
            </div>
        </div>



        <!-- Locales -->
        <div class="flex w-32 items-center justify-between gap-2.5 py-3">
            <div
                class="shimmer h-6 w-6"
                role="presentation"
            >
            </div>

            <div
                class="shimmer h-6 w-14 rounded"
                role="presentation"
            >
            </div>

            <div
                class="shimmer h-6 w-6"
                role="presentation"
            >
            </div>
        </div>
    </div>
</v-topbar>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-topbar-template"
    >
        <div class="flex w-full items-right justify-between border border-b border-l-0 border-r-0 border-t-0 px-4">

            <p class="py-3 text-xs font-medium">
                {{ core()->getConfigData('general.content.header_offer.title') }}

                <a
                    href="{{ core()->getConfigData('general.content.header_offer.redirection_link') }}"
                    class="underline"
                    role="button"
                >
                    {{ core()->getConfigData('general.content.header_offer.redirection_title') }}
                </a>
            </p>
            <div class="flex items-center gap-6 ">

                <div class="top-button ">


            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.currency_switcher.before') !!}


            <!-- Currency Switcher -->
            <x-shop::dropdown
                class="gecmis-islemler-menu1"
                position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'left' : 'right' }}">
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <div
                        class="flex cursor-pointer gap-2.5 py-3 text-sm"
                        role="button"
                        tabindex="0"
                        @click="currencyToggler = ! currencyToggler"
                    >
                        <span>
                            {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                        </span>

                        <span
                            class="text-lg"
                            :class="{'icon-arrow-up': currencyToggler, 'icon-arrow-down': ! currencyToggler}"
                            role="presentation"
                        ></span>
                    </div>
                </x-slot>

                <!-- Dropdown Content -->
                    <x-slot:content class="journal-scroll max-h-[500px] !p-0 rounded-none">
                    <v-currency-switcher></v-currency-switcher>
                </x-slot>
            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.currency_switcher.after') !!}
                </div>
                <div class="top-button gecmis-islemler-menu1">

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.locale_switcher.before') !!}

            <!-- Locales Switcher -->
            <x-shop::dropdown
                class="gecmis-islemler-menu1"
                position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <!-- Dropdown Toggler -->
                    <div
                        class="flex cursor-pointer items-center gap-1.5 py-3 text-sm rounded-none"
                        role="button"
                        tabindex="0"
                        @click="localeToggler = ! localeToggler"
                    >
                        <!--
                        <img
                            src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                    ? core()->getCurrentLocale()->logo_url
                                    : bagisto_asset('images/default-language.svg')
                                }}"
                            class="h-full"
                            alt="@lang('shop::app.components.layouts.header.desktop.top.default-locale')"
                            width="24"
                            height="16"
                        />
                        -->

                        <span>
                            {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                        </span>

                        <span
                            class="text-lg"
                            :class="{'icon-arrow-up': localeToggler, 'icon-arrow-down': ! localeToggler}"
                            role="presentation"
                        ></span>
                    </div>
                </x-slot>

                <!-- Dropdown Content -->
                    <x-slot:content class="journal-scroll max-h-[500px] !p-0 rounded-none">
                    <v-locale-switcher></v-locale-switcher>
                </x-slot>
            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.locale_switcher.after') !!}


                </div>

                <div class="gecmis-islemler-menu1 relative pl-4 before:absolute before:left-0 before:top-0
before:h-full before:w-1 before:bg-red-500 text-sm">


                    <a href="/customer/create-auction"> Satış yap <span class="fa fa-plus"></span></a>
                </div>


            </div>
        </div>
    </script>

    <style>

        .gecmis-islemler-menu1::before {
            content: '';
            position: absolute;
            left: -15px;
            top: -10px  !important;
            height: 200%;
            width: 1px  !important;
            background-color: rgb(229, 231, 235) !important;
        }


    </style>

    <script
        type="text/x-template"
        id="v-currency-switcher-template"
    >
        <div class="my-2.5 grid gap-1 overflow-auto max-md:my-0 sm:max-h-[500px]">
            <span
                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100 text-sm"
                v-for="currency in currencies"
                :class="{'bg-gray-100': currency.code == '{{ core()->getCurrentCurrencyCode() }}'}"
                @click="change(currency)"
            >
                @{{ currency.symbol + ' ' + currency.code }}
            </span>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-locale-switcher-template"
    >
        <div class="my-2.5 grid gap-1 overflow-auto max-md:my-0 sm:max-h-[500px]">
            <span
                class="flex cursor-pointer items-center gap-2.5 px-5 py-2 text-base hover:bg-gray-100"
                :class="{'bg-gray-100': locale.code == '{{ app()->getLocale() }}'}"
                v-for="locale in locales"
                @click="change(locale)"
            >
                <img
                    :src="locale.logo_url || '{{ bagisto_asset('images/default-language.svg') }}'"
                    width="24"
                    height="16"
                />

                @{{ locale.name }}
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-topbar', {
            template: '#v-topbar-template',

            data() {
                return {
                    localeToggler: '',

                    currencyToggler: '',
                };
            },
        });

        app.component('v-currency-switcher', {
            template: '#v-currency-switcher-template',

            data() {
                return {
                    currencies: @json(core()->getCurrentChannel()->currencies),
                };
            },

            methods: {
                change(currency) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('currency', currency.code);

                    window.location.href = url.href;
                }
            }
        });

        app.component('v-locale-switcher', {
            template: '#v-locale-switcher-template',

            data() {
                return {
                    locales: @json(core()->getCurrentChannel()->locales()->orderBy('name')->get()),
                };
            },

            methods: {
                change(locale) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('locale', locale.code);

                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
