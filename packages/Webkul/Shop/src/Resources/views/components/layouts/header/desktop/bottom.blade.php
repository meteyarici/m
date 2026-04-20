{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[78px] w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[10px] ">
    <!--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    -->
    <!-- Left Nagivation Section -->
    <div class="flex items-center  max-[1180px]:gap-x-5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}
        <div style="min-width: 150px;">

        <a
            href="{{ route('shop.home.index') }}"
            aria-label="@lang('shop::app.components.layouts.header.bagisto')"
        >
            <img
                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                width="131"
                height="29"
                alt="{{ config('app.name') }}"
            >
        </a>
        </div>
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        <!-- Category button  -->

        <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'left' : 'right' }}" class="category-btn category-hover-header flex" width="200px">
            <x-slot:toggle>
                <div class="category-btn2 category-hover-header" role="presentation">


                    <span
                        class="fa fa-bars cursor-pointer "  style="font-size:12px"


                    ></span>
                    <span>Kategoriler</span><div></div></div>

            </x-slot:toggle>
            <x-slot:content class="!m-0" >

                <div class="grid grid-cols-3 gap-1 category-grid"  onclick="event.stopPropagation()">

                    <div class="grid grid-cols-2 gap-1 ">
                    <div class="category-grid-col">
                        <p class="font-medium text-gray-900">Clothing</p>
                        <ul class="mt-1 space-y-4">
                            <li><a class="text-gray-500 hover:text-gray-900" href="http://localhost/cloths">Tops</a></li>
                            <li><a class="text-gray-500 hover:text-gray-900">Dresses</a></li>
                            <li><a class="text-gray-500 hover:text-gray-900">Pants</a></li>
                            <li><a class="text-gray-500 hover:text-gray-900">Denim</a></li>
                        </ul>
                    </div>
                        <div class="category-grid-col">
                            <p class="font-medium text-gray-900">Clothing</p>
                            <ul class="mt-6 space-y-4">
                                <li><a class="text-gray-500 hover:text-gray-900">Watches</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Wallets</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Bags</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-1 ">
                        <div class="category-grid-col">
                            <p class="font-medium text-gray-900">Accessories</p>
                            <ul class="mt-6 space-y-4">
                                <li><a class="text-gray-500 hover:text-gray-900">T-Shirts</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Jackets</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Activewear</a></li>
                            </ul>
                        </div>
                        <div class="category-grid-col">
                            <p class="font-medium text-gray-900">Heediyelik</p>
                            <ul class="mt-6 space-y-4">
                                <li><a class="text-gray-500 hover:text-gray-900">Tops</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Dresses</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Pants</a></li>
                                <li><a class="text-gray-500 hover:text-gray-900">Denim</a></li>
                            </ul>
                        </div>
                    </div>


                    <div class="category-grid-col">02</div>
                    <div class="category-grid-col">03</div>
                    <div class="category-grid-col">04</div>
                    <div class="category-grid-col ">05</div>
                    <div class="category-grid-col">06</div>
                    <div class="category-grid-col">07</div>
                </div>

                <!-- 'Women' tab panel, show/hide based on tab state. -->
                <div
                    id="tabs-1-panel-1"
                    x-show="tabSelect==1"
                    role="tabpanel"
                    tabindex="0"
                    class="grid grid-cols-4 gap-x-10 gap-y-12 px-6 pb-10 pt-10"
                >

                </div>

            </x-slot:content>
        </x-shop::dropdown>

        <!--
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}


        <v-desktop-category>
            <div class="flex items-center gap-5">
                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>
                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>
                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}

        -->

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        @if (core()->getConfigData('suggestion.suggestion.general.status'))
            <v-suggestion-searchbar></v-suggestion-searchbar>
        @else
            <form
                action="{{ route('shop.search.index') }}"
                class="flex max-w-[445px] items-center "
                role="search"
            >

                <div class="relative ">
                    @if (core()->getConfigData('suggestion.suggestion.general.status'))
                        <v-suggestion-searchbar></v-suggestion-searchbar>
                    @else

                        <div class="flex h-screen flex-col items-center bg-gray-50 pt-56 " style="width:626px">
                            <div class="w-full max-w-lg overflow-hidden rounded-xl bg-white p-5  ">
                                <div class="flex overflow-hidden rounded-md bg-gray-200 focus:outline focus:outline-blue-500 graybrd">
                                    <input
                                        type="text"
                                        class="w-full rounded-bl-md rounded-tl-md bg-gray-100 px-4 py-2.5 text-gray-700 focus:outline-blue-500"
                                        name="query"
                                        value="{{ request('query') }}"
                                        placeholder="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                                        aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                                        aria-required="true"
                                        required

                                    />
                                    <button type="submit" class="flex items-center gap-2 bg-amber-700 px-3.5 text-white duration-150 hover:bg-blue-600">
                                        <span class="text-white">Ara</span>
                                        <span class="icon-search text-xl lhfix1 text-white"></span>
                                    </button>
                                    @if (core()->getConfigData('general.content.shop.image_search'))
                                        @include('shop::search.images.index')
                                    @endif
                                </div>
                            </div>
                        </div>

                </div>
                @endif
            </form>
        @endif

        <!-- Search Bar Container -->
        <!--
        <div class="relative w-full">
            @if (core()->getConfigData('suggestion.suggestion.general.status'))
            <v-suggestion-searchbar></v-suggestion-searchbar>
@else
            <form
                action="{{ route('shop.search.index') }}"
                    class="flex max-w-[445px] items-center"
                    role="search"
                >
                    <label
                        for="organic-search"
                        class="sr-only"
                    >
                        @lang('shop::app.components.layouts.header.search')
            </label>

            <div class="icon-search pointer-events-none absolute top-2.5 flex items-center text-xl ltr:left-3 rtl:right-3"></div>

            <input
                class="searchbox"
                type="text"
                name="query"
                value="{{ request('query') }}"
                        class="block w-full rounded-lg border border-transparent bg-[#F5F5F5] px-11 py-3 text-xs font-medium text-gray-900 transition-all hover:border-gray-400 focus:border-gray-400"
                        placeholder="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                        aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                        aria-required="true"
                        required

                    >

                    <button type="submit" class="hidden" aria-label="Submit"></button>

                    @if (core()->getConfigData('general.content.shop.image_search'))
                @include('shop::search.images.index')
            @endif
            </form>
@endif
        </div> -->

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex items-center gap-x-2 max-[1100px]:gap-x-2 max-lg:gap-x-2">



        <!-- Right Navigation Links -->
        <div class="mt-1.5 flex gap-x-2 max-[1100px]:gap-x-2 max-lg:gap-x-2">

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            <!-- Compare -->
            @if(core()->getConfigData('general.content.shop.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.compare')"
                >
                    <span
                        class="icon-compare inline-block cursor-pointer text-2xl"
                        role="presentation"
                    ></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

            <!-- Mini cart -->
            @include('shop::checkout.cart.mini-cart')

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            <!-- user profile -->




            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}" class="category-btn category-hover-header flex" width="200px" >
                <x-slot:toggle>
                    <div class="flex items-center gap-2">
                        <span class="fa fa-user cursor-pointer "  style="font-size:12px"></span>

                        @auth('customer')
                            <span class="text-sm font-medium">
                {{ auth()->guard('customer')->user()->first_name }}
            </span>
                        @endauth

                        @guest('customer')
                            <span class="text-sm">
                Giriş Yap
            </span>
                        @endguest
                    </div>
                </x-slot>

                <!-- Guest Dropdown -->
                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-2.5">
                            <p class="font-dmserif text-xl">
                                @lang('shop::app.components.layouts.header.desktop.bottom.welcome-guest')
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                            </p>
                        </div>

                        <p class="py-2px mt-3 w-full border border-[#E9E9E9]"></p>

                        <div class="mt-6 flex gap-4">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="primary-button m-0 mx-auto block w-max rounded-2xl px-7 text-center text-base ltr:ml-0 rtl:mr-0"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="secondary-button m-0 mx-auto block w-max rounded-2xl border-2 px-7 text-center text-base ltr:ml-0 rtl:mr-0"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.sign-up')
                            </a>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up__button.after') !!}
                        </div>
                    </x-slot>
                @endguest

                <!-- Customers Dropdown -->
                @auth('customer')
                    <x-slot:content class="!p-0">
                        <div class="grid gap-2.5 p-5 pb-0">
                            <p class="font-dmserif text-xl">
                                @lang('shop::app.components.layouts.header.desktop.bottom.welcome')’
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                            </p>
                        </div>

                        <p class="py-2px mt-3 w-full border border-[#E9E9E9]"></p>

                        <div class="mt-2.5 grid gap-1 pb-2.5">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                            <a
                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.profile')
                            </a>

                            <a
                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.orders')
                            </a>

                            @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="{{ route('shop.customers.account.wishlist.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.wishlist')
                                </a>
                            @endif

                            <!--Customers logout-->
                            @auth('customer')
                                <x-shop::form
                                    method="DELETE"
                                    action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogout"
                                />

                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                    href="{{ route('shop.customer.session.destroy') }}"
                                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.logout')
                                </a>
                            @endauth

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
                        </div>
                    </x-slot>
                @endauth
            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-desktop-category-template">
        <div
            class="flex items-center gap-5"
            v-if="isLoading"
        >
            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>
            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>
            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>
        </div>

        <div
            class="flex items-center"
            v-else
        >
            <div
                class="group relative flex h-[77px] items-center border-b-[4px] border-transparent hover:border-b-[4px] hover:border-navyBlue"
                v-for="category in categories"
            >
                <span>
                    <a
                        :href="category.url"
                        class="inline-block px-5 uppercase"
                        v-text="category.name"
                    >
                    </a>
                </span>

                <div
                    class="pointer-events-none absolute top-[78px] z-[1] max-h-[580px] w-max max-w-[1260px] translate-y-1 overflow-auto overflow-x-auto border border-b-0 border-l-0 border-r-0 border-t border-[#F3F3F3] bg-white p-9 opacity-0 shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] transition duration-300 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-hover:duration-200 group-hover:ease-in ltr:-left-9 rtl:-right-9"
                    v-if="category.children.length"
                >
                    <div class="aigns flex justify-between gap-x-[70px]">
                        <div
                            class="grid w-full min-w-max max-w-[150px] flex-auto grid-cols-[1fr] content-start gap-5"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="font-medium text-navyBlue">
                                    <a
                                        :href="secondLevelCategory.url"
                                        v-text="secondLevelCategory.name"
                                    >
                                    </a>
                                </p>

                                <ul
                                    class="grid grid-cols-[1fr] gap-3"
                                    v-if="secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-sm font-medium text-[#6E6E6E]"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a
                                            :href="thirdLevelCategory.url"
                                            v-text="thirdLevelCategory.name"
                                        >
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return  {
                    isLoading: true,

                    categories: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.isLoading = false;

                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                }
            },
        });
    </script>
@endPushOnce

@if (core()->getConfigData('suggestion.suggestion.general.status'))
    @pushOnce('scripts')
    <script type="text/x-template" id="v-suggestion-searchbar-template">
        <div>
            <div class="relative w-full">
                <div class="flex max-w-[645px] items-left">

                    <form
                        action="{{ route('shop.search.index') }}"
                        class="flex max-w-[645px] items-center"
                        role="search"
                    >
                        <label
                            for="organic-search"
                            class="sr-only"
                        >
                            @lang('shop::app.components.layouts.header.search')
                        </label>

                        <div class="flex h-screen flex-col items-center bg-gray-50 pt-56 " style="width:626px">
                            <div class="w-full max-w-xl overflow-hidden rounded-xl bg-white p-5  ">
                                <div class="flex overflow-hidden rounded-md bg-gray-200 focus:outline focus:outline-blue-500 graybrd">

                        <input
                            type="text"
                            name="query"

                            value="{{ request('query') }}"
                            class="w-full rounded-bl-md rounded-tl-md bg-gray-100 px-4 py-2.5 text-gray-700 focus:outline-blue-500"
                            placeholder="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                            aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                            aria-required="true"
                            v-model="term"
                            autocomplete="off"
                            onblur="setTimeout(function() {if(document.querySelector('#suggest')){document.querySelector('#suggest').classList.add('hidden')}}, 300)"
                            onfocus="if(document.querySelector('#suggest'))document.querySelector('#suggest').classList.remove('hidden')"
                            @keyup="search()"
                            required
                        >

                        <button type="submit" class="flex items-center gap-2 bg-amber-700 px-3.5 text-white duration-150 hover:bg-blue-600">
                            <span class="text-white">Ara</span>
                            <span class="icon-search text-xl lhfix1 text-white"></span>
                        </button>

                        @if (core()->getConfigData('general.content.shop.image_search'))
                            @include('shop::search.images.index')
                        @endif
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                <!-- suggeesstions -->

            <div
                class="absolute z-10 max-h-96 overflow-auto rounded  w-dynamic" id="suggest"
                v-if="term.length >= config.minSearchTerms"
                style="left:20px; top:68px;
                width:550px;"
            >
                <div
                    :class="config.display === 'ar' ? 'ar' : ''"
                    v-if="suggestsResults.length"
                >
                    <span
                        v-for="(result, index) in suggestsResults"
                    >
                        <div v-if="index < config.noOfTerms">
                            <a :href="result.url_key">
                                <div class="h-8 border bg-white p-2 text-sm hover:bg-gray-200 border-blue-100 hover:border-red-100">
                                    <p
                                        :class="config.display === 'ar' ? 'mr-1' : ''"
                                        class="overflow-hidden text-ellipsis whitespace-nowrap"
                                    >
                                        <span v-html="result.name"></span>

                                        @if (core()->getConfigData('suggestion.suggestion.general.display_categories_toggle'))
                                            <span v-if="result.categories.length">
                                                @lang('suggestion::app.shop.search-suggestion.in')
                                                <span
                                                    class="font-semibold"
                                                    v-for="(category, index) in result.categories"
                                                >
                                                    <template v-if="index < result.categories.length - 1">
                                                        @{{ category.name }},
                                                    </template>
                                                    <template v-else>
                                                        @{{ category.name }}
                                                    </template>
                                                </span>
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </a>
                        </div>
                    </span>

                    @if(core()->getConfigData('suggestion.suggestion.general.display_terms_toggle'))
                        <a :href="'search?query=' + term + '&sort=price-desc&limit=12&mode=grid'">
                            <div class="h-9   hover:border-red-100 bg-white p-2  hover:bg-gray-200">
                                <div v-if="config.display === 'ar'">
                                    @{{  term }}
                                    <span
                                        class="float-left"
                                    >
                                    @{{ suggestsResults.length }}
                                    </span>
                                </div>

                                <p v-else>
                                    @{{ term }}
                                    <span
                                        class="float-right"
                                        :class="config.display === 'ar' ? 'ml-1' : 'mr-1'"
                                    >
                                        @{{ suggestsResults.length }}
                                    </span>
                                </p>
                            </div>
                        </a>
                    @endif

                    @if(core()->getConfigData('suggestion.suggestion.general.display_product_toggle'))
                        <div class="h-9 bg-white  p-2 text-left test-sm " >
                            <p class="text-sm ">@lang('suggestion::app.shop.search-suggestion.popular-products')</p>
                        </div>
                        <a
                            :href="result.url_key"
                            v-for="(result, index) in productResults"
                        >
                            <div class="flex w-full  bg-white hover:bg-gray-200 border-blue-100 hover:border-red-100">
                                <div class="w-1/4">
                                    <img
                                        class="max-h-20 min-h-20 min-w-20 max-w-20 p-2 rounded-full"
                                        v-if="result.images.length"
                                        :src="result.images[0].url"
                                    />
                                </div>
                                <div class="w-3/4 p-1">
                                    <div
                                        class="m-4 overflow-hidden text-ellipsis whitespace-nowrap"
                                        :class="config.display === 'ar' ? 'mr-2' : ''"
                                    >
                                        <span v-html="result.name"></span>
                                        <br>
                                        <div
                                            class="product-price gap-3 flex"
                                            v-html="result.price_html"
                                        >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a
                            href="javascript:void(0)"
                            class="show-more-btn"
                            v-if="showMoreButton"
                            @click="loadMoreResults"
                        >
                            @lang('suggestion::app.shop.search-suggestion.text-more')
                        </a>
                    @endif

                </div>

                <div
                    class="h-10 border bg-white p-2"
                    :class="config.display === 'ar' ? 'ar' : ''"
                    v-if="isSearching"
                >
                    <p>@lang('suggestion::app.shop.search-suggestion.searching')  <span class="searchLoading"></span></p>
                </div>
                <div
                    class="h-10 border bg-white p-2"
                    :class="config.display === 'ar' ? 'ar' : ''"
                    v-if="! isSearching && ! suggestsResults.length"
                >
                    <p>@lang('suggestion::app.shop.search-suggestion.no-results')</p>
                </div>
            </div>
            </div>
        </div>
    </script>


    <style>
        .category-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 50px;
            border-radius: 5px;
            background: #F3F4F6;
            padding: 15px 16px;
            cursor: pointer;
            font-weight: 400;
            color: #2C3C28;
            position: relative;
            font-size: 0.875rem;
            line-height: 1.25rem;

        }

        .category-btn2 {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 50px;
            border-radius: 5px;
            padding: 15px 16px;
            font-weight: 400;
            position: relative;
            font-size: 0.875rem;
            line-height: 1.25rem;

        }

        .category-btn:hover {
            background: rgb(234 139 16 / 1) !important;
        }

        /* sadece ilk div içindeki ilk span */
        .category-btn:hover > div:first-child > div:first-child > span:first-of-type,
        .category-btn:hover > div:first-child > div:first-child > span:nth-of-type(2)
        {
            color: #fff !important;
        }

        .category-btn:hover > div:first-child > span:first-child > span:first-of-type,
        .category-btn:hover > div:first-child > span:first-child > span:nth-of-type(2)
        {
            color: #fff !important;
        }

        .searchbox{
            background: #42b982;
        }

        .search-header{
            position: relative;
        }

        .lhfix1{
            line-height: 2 !important;
            color:#fff;
        }

        .graybrd{
            border:2px solid #F3F4F6 !important;
        }

        .w-dynamic{ min-width: 450px;}

        .category-grid{
            width: 900px;
        }

        .category-grid-col{
            border:1px solid #ccc;
        }


        .searchLoading::after {
            content: "...";
            display: inline-block;
            width: 1.5em;
            overflow: hidden;
            vertical-align: bottom;
            animation: dots 1.4s steps(4, end) infinite;
        }

        @keyframes dots {
            from { width: 0; }
            to   { width: 1.5em; }
        }

        .category-grid a{

        }

        .category-grid a:hover{

            color:rgb(234 139 16 / 1) !important;
        }
    </style>
    <script type="module">
        app.component('v-suggestion-searchbar', {
            template: '#v-suggestion-searchbar-template',

            data() {
                return {
                    term: '',

                    category: '',

                    isSearching: false,

                    productResults: [],

                    suggestsResults: [],

                    highlightedResults: [],

                    visibleProductsCount: 10,

                    config: {
                        displayProductToggle: "{{ core()->getConfigData('suggestion.suggestion.general.display_product_toggle') }}",

                        noOfTerms: "{{ core()->getConfigData('suggestion.suggestion.general.show_products') }}",

                        displayTermsToggle: "{{ core()->getConfigData('suggestion.suggestion.general.display_terms_toggle') }}",

                        displayCategory: "{{ core()->getConfigData('suggestion.suggestion.general.display_categories_toggle') }}",

                        minSearchTerms: "{{ core()->getConfigData('suggestion.suggestion.general.min_search_terms') }}",

                        display: "{{ core()->getCurrentLocale()->code }}"
                    },
                };
            },

            computed: {
                showMoreButton() {
                    return this.visibleProductsCount < this.suggestsResults.length;
                }
            },

            methods: {
                search() {
                    if (this.term.length >= this.config.minSearchTerms) {
                        this.isSearching = true;

                        this.$axios.get("{{ route('search_suggestion.search.index') }}", {
                            params: { term: this.term, category: this.category }
                        })
                            .then (response => {
                                this.handleResponse(response.data);
                            })
                            .catch (error => {
                                console.error("Error:", error);
                            })
                    } else {
                        this.suggestsResults = [];
                    }
                },

                handleResponse(data) {
                    const escapeHtml = (unsafe) => {
                        return unsafe
                            .replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/"/g, "&quot;")
                            .replace(/'/g, "&#039;");
                    };

                    const searchTerm = this.term.toLowerCase();

                    const searchTermReversed = searchTerm.split('').reverse().join('');

                    const results = data.data;

                    const formattedResults = results.map(result => {
                        const originalText = result.name.toLowerCase();

                        const index1 = originalText.indexOf(searchTerm);

                        const index2 = originalText.indexOf(searchTermReversed);

                        let formattedName = escapeHtml(result.name);

                        if (index1 !== -1 || index2 !== -1) {
                            const startIndex = index1 !== -1 ? index1 : index2;

                            const foundTerm = index1 !== -1 ? searchTerm : searchTermReversed;

                            const escapedName = escapeHtml(result.name);

                            formattedName = `${escapedName.slice(0, startIndex)}<span class="font-semibold">${escapedName.slice(startIndex, startIndex + foundTerm.length)}</span>${escapedName.slice(startIndex + foundTerm.length)}`;
                        }

                        return { ...result, name: formattedName };
                    });

                    this.suggestsResults = formattedResults;

                    this.isSearching = false;
                },

                loadMoreResults() {
                    this.visibleProductsCount += 10;

                    this.updateDisplayedResults();
                },

                updateDisplayedResults() {
                    this.productResults = this.suggestsResults.slice(0, this.visibleProductsCount);
                },

                focusInput(event) {
                    $(event.target.parentElement.parentElement).find('input').focus();

                    this.search();
                },

                submitForm() {
                    if (this.term !== '') {
                        document.getElementsByName('term')[0].value = this.term;

                        document.getElementById('search-form').submit();
                    }
                },

                triggerInternalClick() {
                    const containerElement = this.$el;

                    // 2. Query Selector kullanarak, dış elemanın içindeki İLK div elemanını buluyoruz.
                    // 'div' sorgusu, kapsayıcı içindeki ilk div'i döndürür.
                    const targetElement = containerElement.querySelector('div');

                    if (targetElement) {
                        // 3. Elemanın native DOM click() metodunu çağırıyoruz.
                        targetElement.click();

                        console.log("İçindeki ilk div tetiklendi:", targetElement);
                    } else {
                        console.log("İçinde div bulunamadı.");
                    }
                }
            },

            watch: {
                suggestsResults: {
                    immediate: true,
                    handler() {
                        this.updateDisplayedResults();
                    }
                }
            }
        });


            let isProgrammaticClick = false;

            document.addEventListener('click', function (e) {
            if (isProgrammaticClick) return;

            const categoryBtn = e.target.closest('.category-btn');
            if (!categoryBtn) return;

            const innerDiv = categoryBtn.querySelector('.select-none');
            if (!innerDiv) return;


            if (e.target === innerDiv || innerDiv.contains(e.target)) {
            return;
        }


            isProgrammaticClick = true;

            innerDiv.dispatchEvent(new MouseEvent('click', {
            bubbles: true,
            cancelable: true
        }));

            setTimeout(() => {
            isProgrammaticClick = false;
        }, 0);

        }, false);




    </script>
    @endPushOnce
@endif

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
