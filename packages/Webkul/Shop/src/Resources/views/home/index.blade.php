@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    @if(isset($categories))
    <script>
        localStorage.setItem('categories', JSON.stringify(@json($categories)));
    </script>
    @endif
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        <!-- Static content -->
        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <!-- Image Carousel -->
                <div class=" flex justify-between  gap-1  container mt-4 max-lg:px-8 max-md:mt-7 max-md:!px-0 max-sm:mt-5">
                    <div class="w-full" style="width: 960px">

                        <x-shop::collections.carousel
                            :title="$data['title'] ?? ''"
                            :src="route('shop.api.products.index', $data['filters'] ?? [])"
                            :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                            aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                        />



                    </div>

                    <div style="width: 360px;" class="flex gap-1 flex-col md:flex-row">
                        <div
                            class="group w-full rounded-lg border border-gray-100 bg-white p-2 transition-all duration-300 hover:shadow-md product-card-main "
                            v-if="mode != 'list'"
                        >
                            <img src="/assets/images/col/1.png" style="height: 80px; border-radius: 8px; ">

                        </div>

                        <div
                            class="group w-full rounded-lg border border-gray-100 bg-white p-2 transition-all duration-300 hover:shadow-md product-card-main "
                            v-if="mode != 'list'"
                        >
                            <img src="/assets/images/col/2.png" style="height: 80px; border-radius: 8px; ">

                        </div>

                        <div
                            class="group w-full rounded-lg border border-gray-100 bg-white p-2 transition-all duration-300 hover:shadow-md product-card-main "
                            v-if="mode != 'list'"
                        >
                            <img src="/assets/images/col/3.png" style="height: 80px; border-radius: 8px; ">

                        </div>

                    </div>
                </div>



                @break
            @case ($customization::STATIC_CONTENT)
                <!-- push style -->
                @if (! empty($data['css']))
                    @push ('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                <!-- render html -->
                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif

                @break
            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />

            Yakında başlayacak...
                @break
            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />

                @break
        @endswitch
    @endforeach
</x-shop::layouts>
