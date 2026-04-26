<x-shop::layouts :has-header="false">
    <x-slot:title>
        Auction Create
    </x-slot>




    <header class="w-full bg-white border-b border-zinc-100 py-4 mb-8">
        <div class="container max-w-[1200px] mx-auto flex justify-between items-center px-6">



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

            <div class="flex items-center gap-4">
                <div class="text-right max-sm:hidden">
                    <p class="text-xs font-semibold text-zinc-900 leading-none">
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </p>
                </div>

                <div class="w-10 h-10 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-600 shadow-sm overflow-hidden">
                    @if(auth()->user()->image)
                        <img src="{{ auth()->user()->image_url }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    @endif
                </div>
            </div>

        </div>
    </header>



    <div class="container mt-8 max-1180:px-5 max-md:mt-6 max-md:px-4">
        <div
            id="app"
            class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200
                   p-16 px-[50px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0"
        >





            <ol id="stepsSection" class="flex justify-items-end w-full space-x-4 steps">
                <li id="step-header-1" class=" flex w-full items-center text-fg-brand liafter after:content-[''] after:w-full after:h-1 after:border-b after:border-brand-subtle after:border-4 after:inline-block after:ms-4 after:rounded-full"
                    style=" padding: 10px;">
        <span class="flex items-center justify-center bg-brand-softer rounded-full lg:h-12 lg:w-12 shrink-0">
           <span class=" flex items-center justify-center" style="    width: 35px;">
            <svg class="w-5 h-5 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4M3 9v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4H3V5Z"/>
</svg>
            </span>
            <span>   <div class="shrink-0">
                            <h6 class="text-base-content mb-0.5">Kategori</h6>
                            <p class="text-base-content/50 text-xs">Kategori seçin</p>
                        </div></span>
        </span>
                </li>
                <li id="step-header-2" class="stepdisabled flex w-full items-center text-fg-brand liafter after:content-[''] after:w-full after:h-1 after:border-b after:border-brand-subtle after:border-4 after:inline-block after:ms-4 after:rounded-full"
                    style=" padding: 10px;">
        <span class="flex items-center justify-center bg-brand-softer rounded-full lg:h-12 lg:w-12 shrink-0">
           <span class=" flex items-center justify-center" style="    width: 35px;">
                   <svg class="w-5 h-5 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round"
                                                              stroke-linejoin="round" stroke-width="2"
                                                              d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/></svg>
            </span>
            <span>   <div class="shrink-0">
                            <h6 class="text-base-content mb-0.5">Medya</h6>
                            <p class="text-base-content/50 text-xs">Resim ve Video ekleyin</p>
                        </div></span>
        </span>
                </li>
                <li id="step-header-3" class="stepdisabled flex w-full items-center liafter after:content-[''] after:w-full after:h-1 after:border-b after:border-default after:border-4 after:inline-block  after:ms-4 after:rounded-full">
        <span class="flex items-center justify-center  bg-neutral-tertiary rounded-full lg:h-12 lg:w-12 shrink-0">
             <span class="w-10 h-10 flex items-center justify-center">

                 <svg class="w-5 h-5 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                      fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/></svg>
        </span>
         <span>
                   <div class="shrink-0">
                            <h6 class="text-base-content mb-0.5">Bilgiler</h6>
                            <p class="text-base-content/50 text-xs">Fiyat ve diğer bilgiler</p>
                        </div>
             </span>
        </span>
                </li>
                <li id="step-header-4" class="stepdisabled flex items-center w-full">
        <span
            class="flex items-center justify-center liafter bg-neutral-tertiary rounded-full lg:h-12 lg:w-12 shrink-0">
        <span class="w-10 h-10 flex items-center justify-center">

             <svg style="width:25px; height: 25px;" class="text-fg-brand" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><rect
                     width="24" height="24" fill="" rx="16"/><path stroke="currentColor" stroke-linecap="round"
                                                                   stroke-linejoin="round" stroke-width="2"
                                                                   d="M5 11.917 9.724 16.5 19 7.5"/></svg>
           </span>
            <span>
         <div class="shrink-0">
                            <h6 class="text-base-content mb-0.5">Yayınla</h6>
                            <p class="text-base-content/50 text-xs">Onaya gödnerin</p>
                        </div>
        </span>

        </span>

                </li>
            </ol>

            <div id="auctionFormContainer">
            <auction-wizard v-if="!isSubmitted">



                    <div class="animate-pulse">
                        <div class="flex items-center justify-between mb-6">
                            <div class="relative w-full max-w-[200px]">
                                <div class="h-8 bg-zinc-100 rounded-lg w-full border border-zinc-50"></div>
                            </div>
                            <div class="h-4 bg-zinc-50 rounded w-32"></div>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            @for ($i = 0; $i < 21; $i++)
                                <div class="flex items-center gap-3 p-2.5 rounded-lg border border-zinc-100 bg-white">
                                    <div class="w-6 h-6 bg-zinc-100 rounded-md"></div>
                                    <div class="h-3 bg-zinc-100 rounded w-16"></div>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-8 space-y-4 pt-4 border-t border-zinc-50">
                            <div class="flex items-start gap-2">
                                <div class="w-4 h-4 bg-zinc-100 rounded-full mt-0.5"></div>
                                <div class="space-y-2 flex-1">
                                    <div class="h-3 bg-zinc-100 rounded w-full"></div>
                                    <div class="h-3 bg-zinc-50 rounded w-2/3"></div>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="w-4 h-4 bg-zinc-100 rounded-full mt-0.5"></div>
                                <div class="h-3 bg-zinc-100 rounded w-1/2"></div>
                            </div>

                            <div class="ml-6 p-4 bg-zinc-50/50 rounded-lg border border-zinc-50 h-24 w-full"></div>
                        </div>

                        <div class="flex justify-end mt-12 pt-6 border-t border-zinc-50">
                            <div class="h-11 w-36 bg-zinc-200 rounded-xl"></div>
                        </div>
                    </div>



            </auction-wizard>
            </div>

            <div id="successResultSection"   class="max-w-[600px] mx-auto py-16 px-4 animate-fade-in hidden">
                <div class="bg-white  rounded-3xl  overflow-hidden">
                    <div class="p-8 text-center border-b border-zinc-100 bg-emerald-50/30">
                        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-zinc-900">Müzayedeniz Onaya Gönderildi!</h2>
                        <p class="text-zinc-500 mt-2 text-sm leading-relaxed">
                            İncelemelerimiz genellikle 15-30 dakika sürmektedir. <br> Onaylandığında e-posta ile bilgilendirileceksiniz.
                        </p>
                    </div>



                    <div class="p-8 bg-zinc-50 space-y-3">
                        <h4 class="text-xs font-bold text-zinc-400  tracking-widest mb-4">Şimdi ne yapmak istersiniz?</h4>

                        <a href="/auctions" class="flex items-center justify-between p-4 bg-white border border-zinc-200 rounded-xl transition-all group border-hover2">
                            <span class="text-sm font-medium text-zinc-700">Açık Mezatları İncele</span>
                            <svg class="w-4 h-4 text-zinc-300 group-hover:text-zinc-900 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>

                        <a href="/account/auctions" class="flex items-center justify-between p-4 bg-white border border-zinc-200 rounded-xl  transition-all group border-hover2">
                            <span class="text-sm font-medium text-zinc-700">Mezatlarımın Onay Durumunu Kontrol Et</span>
                            <svg class="w-4 h-4 text-zinc-300 group-hover:text-zinc-900 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>
                        <a href="/customer/create-auction" class="flex items-center justify-between p-4 bg-white border border-zinc-200 rounded-xl transition-all group border-hover2">
                            <span class="text-sm font-medium text-zinc-700"> Yeni Mezat Oluştur</span>
                            <svg class="w-4 h-4 text-zinc-300 group-hover:text-zinc-900 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>


                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= DROPZONE TEMPLATE ================= --}}
    @pushOnce('scripts')
        <script type="text/x-template" id="v-dropzone-template">
            <form
                ref="dropzone"
                class="dropzone dz-clickable"
                style="min-height:200px;border:2px dashed #ddd;border-radius:12px;"
            >
                <meta name="csrf-token" content="{{ csrf_token() }}">
                @csrf
                <div class="dz-message flex flex-col items-center justify-center  py-10  transition-colors group cursor-pointer">
                    <div class="mb-3 p-3 bg-white rounded-full shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-zinc-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <div class="flex flex-col text-center">
                        <span class="text-sm font-semibold text-zinc-700">Görselleri buraya sürükleyin</span>
                        <span class="text-xs text-zinc-400 mt-1">veya tıklayarak dosya seçin</span>
                    </div>

                    <div class="mt-4 px-3 py-1 bg-zinc-100 rounded-full text-[10px] text-zinc-500 font-medium">
                        PNG, JPG veya WEBP (Maks. 5MB)
                    </div>
                </div>
            </form>
        </script>
    @endpushOnce

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css"/>
    @endpush

    @pushOnce('scripts')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        {{-- ================= AUCTION WIZARD TEMPLATE ================= --}}
        <script type="text/x-template" id="auction-wizard-template">

            <div>

                <div class="w-full bg-gray-200 h-2 rounded overflow-hidden lineBottom">
                    <div
                        id="progressBar"
                        class="h-full transition-all duration-500 ease-in-out"
                        :style="progressStyle"
                        style="width:25%; background:#ff4c00;"
                    ></div>
                </div>

                <br />
                <div v-show="step === 1" class="space-y-4">
                    <div class="flex items-center justify-between mb-4">




                        <div class="relative w-full max-w-[200px]">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-zinc-400 searchboxicon">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Kategori ara..."
                                class="searchbox w-full pl-8 pr-3 py-1.5 text-xs border border-zinc-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all"
                            >
                        </div>


                        <span v-if="selectedSubCategory" class="text-xs  font-medium  px-2 py-1 rounded">
                            Seçildi: <span class="text-emerald-600"> @{{ selectedCategory.name }} > @{{ selectedSubCategory.name }}</span>
        </span>
                    </div>

                    <div class="grid grid-cols-3 sm:grid-cols-3 gap-2">
                        <div v-for="cat in categories" :key="cat.id" class="contents">
                            <div
                                @click="toggleCategory(cat)"
                                :class="[
                    'flex items-center gap-3 p-2.5 rounded-lg border border-zinc-200 cursor-pointer transition-all select-none border-hover1',
                    selectedCategory?.id === cat.id ? 'bg-blue-600 border-blue-600 shadow-sm' : 'bg-white hover:bg-zinc-50'
                ]"
                            >
                                <span class="text-lg">@{{ cat.icon }}</span>
                                <span :class="['text-xs font-semibold truncate', selectedCategory?.id === cat.id ? 'text-amber-500' : 'text-zinc-700']">
                    @{{ cat.name }}
                </span>
                            </div>

                            <div
                                v-if="selectedCategory?.id === cat.id"
                                class="col-span-full bg-zinc-50 rounded-lg p-2 my-1 border border-zinc-200 flex flex-wrap gap-1.5 animate-slide-down"
                            >
                                <div
                                    v-for="sub in cat.subCategories"
                                    :key="sub.id"
                                    @click="selectSub(sub)"
                                    :class="[
            'flex items-center gap-1.5 px-2 py-1 rounded-md border text-[10px] cursor-pointer transition-all select-none leading-none',
            selectedSubCategory?.id === sub.id
                ? 'bg-blue-600 border-blue-600 text-white shadow-sm font-bold'
                : 'bg-white border-zinc-200 text-zinc-500 hover:border-zinc-400'
        ]"
                                >
                                    <span class="text-xs">@{{ sub.name }}</span>

                                    <svg v-if="selectedSubCategory?.id === sub.id" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3  pt-4">
                        <div class="flex items-start gap-2 text-[11px] text-xs">
                            <svg class="w-3.5 h-3.5 mt-0.5  shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z" />
                            </svg>
                            <p>İstediğiniz kategoriyi listede bulamazsanız <button class="text-emerald-600 font-bold hover:underline" @click="selectOtherCategory">"Diğer / Her Şey"</button> seçeneğini kullanabilirsiniz.</p>
                        </div>

                        <div class="flex flex-col gap-2 text-xs text-zinc-500">
                            <div class="flex items-start gap-2">
                                <svg class="w-3.5 h-3.5 mt-0.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p>
                                    Bize kategori önermek isterseniz
                                    <button @click="showSuggestionBox = !showSuggestionBox" class="text-emerald-600 font-bold hover:underline">
                                        buraya tıklayın.
                                    </button>
                                </p>
                            </div>

                            <transition name="fade">
                                <div v-if="showSuggestionBox" class="ml-5 p-3 bg-emerald-50  rounded-lg space-y-2">
                                    <p class="text-xs text-emerald-700 font-xs">Önerdiğiniz kategori ismini yazın:</p>
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            v-model="categorySuggestion"
                                            placeholder="Örn: Koleksiyon Kartları..."
                                            class="flex-1 px-2 py-1.5 text-xs border border-emerald-200 rounded focus:outline-none focus:ring-1 focus:ring-emerald-500"
                                        >

                                    </div>
                                    <p v-if="suggestionSent" class="text-xs text-emerald-600 font-bold italic animate-pulse">
                                        ✓ Öneriniz alındı, teşekkürler!
                                    </p>
                                </div>
                            </transition>
                        </div>
                    </div>


                </div>
                <!-- ================= STEP 2 ================= -->
                <div v-show="step === 2">
                    <v-dropzone
                        ref="dropzoneRef"
                        upload-url="/customer/upload-images"
                        @uploaded="images.push($event)"
                        @status-change="isUploading = $event"
                    />
                </div>

                <!-- ================= STEP 2 ================= -->
                <div v-show="step === 3">
                    <form class="w-full bg-white rounded-lg ">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Müzayede Başlığı
                                    </label>
                                    <input
                                        type="text"
                                        class="w-full h-9 px-3 text-xs border border-gray-300 rounded-lg  focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all"
                                        placeholder="Örn: Koleksiyonluk Antika Saat"
                                        @input="clearError('title')"
                                        :class="errors.includes('title') ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                                        v-model="form.title"
                                    />
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Açıklama
                                    </label>
                                    <textarea
                                        rows="3"
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all resize-none"
                                        placeholder="Müzayede hakkında detaylı bilgi verin..."
                                        v-model="form.description"
                                    ></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="grid grid-cols-3 gap-3 p-3 bg-zinc-50 rounded-xl border border-zinc-100">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-tight">Açılış Fiyatı</label>
                                            <div class="relative">
                                                <input type="text" class="w-full h-9 pl-7 pr-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none"
                                                       placeholder="0" @input="updatePrice($event, 'start_price')" />
                                                <span class="absolute left-2.5 top-2.5 text-gray-400 text-xs font-semibold">₺</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-tight">Hemen Al</label>
                                            <div class="relative">
                                                <input type="text" class="w-full h-9 pl-7 pr-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none"
                                                       placeholder="Opsiyonel" @input="updatePrice($event, 'buy_now_price')" />
                                                <span class="absolute left-2.5 top-2.5 text-gray-400 text-xs font-semibold">₺</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase tracking-tight">Min. Artış</label>
                                            <div class="relative">
                                                <input type="text" class="w-full h-9 pl-7 pr-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none"
                                                       placeholder="0" :value="new Intl.NumberFormat('tr-TR').format(form.min_increment)" @input="updatePrice($event, 'min_increment')" />
                                                <span class="absolute left-2.5 top-2.5 text-gray-400 text-xs font-semibold">₺</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="form.start_price > 0" class="mt-2 px-3 py-2  rounded-lg border border-blue-100/50">
                                        <p class=" text-xs leading-relaxed italic">
                                            <span class="text-emerald-600 ">@{{ numberToWords(form.start_price) }}</span> açılış fiyatı,
                                            minimum pey <span class="text-emerald-600 ">@{{ numberToWords(form.min_increment) }}</span>.
                                            <template v-if="form.buy_now_price > 0">
                                                Hemen al fiyatı <span class="text-emerald-600 ">@{{ numberToWords(form.buy_now_price) }}</span>.
                                            </template>
                                        </p>
                                    </div>
                                </div>

                                <div class="md:col-span-2 space-y-4 pt-2">

                                    <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-100">
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    v-model="form.startImmediately"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    @change="if(form.startImmediately) {  clearError('start_at_past'); }"                                                >
                                                <span class="text-xs font-medium text-gray-700">Müzayede hemen başlasın</span>
                                            </label>

                                            <span v-if="form.startImmediately"
                                                  class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                ✓ Yayınlandığı an aktif
            </span>
                                        </div>

                                        <div v-if="!form.startImmediately" class="mt-3 animate-slide-down">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Başlangıç Tarihi Seçin</label>
                                            <input
                                                type="datetime-local"
                                                class="w-full h-9 px-3 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-white shadow-sm"
                                                v-model="form.start_at"
                                                @change="clearError('start_at_past')"
                                                :class="errors.includes('start_at_past') ? 'border-red-500 bg-red-50' : 'border-gray-300'"

                                            />
                                        </div>
                                    </div>

                                    <div class="p-3 mt-3 bg-zinc-50 rounded-xl border border-zinc-100">
                                        <label class="block text-xs font-bold text-gray-500 mb-3  tracking-tight">Müzayede Bitiş Zamanı</label>

                                        <div class="flex flex-wrap gap-4 mb-3">
                                            <label class="flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" v-model="durationMode" value="1_week" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs text-gray-700">1 Hafta</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" v-model="durationMode" value="1_month" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs text-gray-700">1 Ay</span>
                                            </label>

                                            <label class="flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" v-model="durationMode" value="manual" class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs text-gray-700">Tarih Gir</span>
                                            </label>
                                        </div>

                                        <div v-if="durationMode === 'manual'" class="animate-slide-down border-t pt-3">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Bitiş Tarihi Seçin</label>
                                            <input
                                                type="datetime-local"
                                                class="w-full h-9 px-3 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-white shadow-sm"
                                                v-model="form.end_at"
                                                @change="clearError('end_at_before_start')"
                                                :class="errors.includes('end_at_before_start') ? 'border-red-500 bg-red-50' : 'border-gray-300'"

                                            />
                                        </div>
                                    </div>

                                </div>

                                <div class="md:col-span-2 space-y-4 pt-2">
                                    <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100">
                                        <label class="block text-xs font-bold text-gray-500 mb-4  tracking-tight">Teslimat ve Lokasyon</label>

                                        <div class="flex flex-wrap gap-6 mb-2 pb-4 border-b border-zinc-200/60">
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" v-model="form.delivery_method" value="optional" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-medium text-gray-700 group-hover:text-blue-600 transition-colors">Opsiyonel</span>
                                            </label>

                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" v-model="form.delivery_method" value="pickup" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-medium text-gray-700 group-hover:text-blue-600 transition-colors">Yerinde Teslim</span>
                                            </label>

                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="radio" v-model="form.delivery_method" value="shipping" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs font-medium text-gray-700 group-hover:text-blue-600 transition-colors">Kargo ile Gönderilir</span>
                                            </label>
                                        </div>

                                        <div v-if="form.delivery_method !== 'optional'" class="mt-4 animate-slide-down">
                                            <div class="grid grid-cols-2 gap-3 mb-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase">İl</label>
                                                    <select v-model="form.city" @change="handleCityChange(); clearError('city')"
                                                            class="w-full h-9 px-2 text-xs border border-gray-300 rounded-lg outline-none bg-white"

                                                            :class="errors.includes('city') ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                                                    >
                                                        <option value="">İl Seçin</option>
                                                        <option v-for="city in cities" :key="city.id" :value="city.name">
                                                            @{{ city.name }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase">İlçe</label>
                                                    <select v-model="form.district" :disabled="districts.length === 0" class="w-full h-9 px-2 text-xs border border-gray-300 rounded-lg outline-none bg-white disabled:bg-gray-100"
                                                            @change="clearError('district')"
                                                            :class="errors.includes('district') ? 'border-red-500 bg-red-50' : 'border-gray-300'"
                                                    >
                                                        <option value="">@{{ districts.length > 0 ? 'İlçe Seçin' : 'Önce İl Seçin' }}</option>
                                                        <option v-for="district in districts" :key="district.id" :value="district.name">
                                                            @{{ district.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Nakliye / Teslimat Notu</label>
                                                <textarea
                                                    v-model="form.delivery_note"
                                                    rows="2"
                                                    class="w-full p-3 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-white resize-none"
                                                    placeholder="Örn: Kargo alıcıya aittir veya sadece hafta içi teslimat yapılır."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </form>
                </div>

                <!-- ================= STEP 3 ================= -->
                <div v-show="step === 4">
                    <div class="flex flex-wrap gap-3 mb-6" id="image-grid">
                        <div
                            v-for="(img, i) in images"
                            :key="i"
                            :data-id="i"
                            class="group relative w-28 h-28 border-2 border-white rounded-xl overflow-hidden shadow-sm cursor-move transition-all hover:border-blue-500 bg-white"
                        >
                            <img :src="img.url" class="w-full h-full object-cover pointer-events-none">
                            <div
                                @click.stop="removeImage(i)"
                                class="absolute top-1 right-1 w-6 h-6 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer shadow-md z-10"
                                style="top:1px !important; background: #fff !important;"
                            >
                                <span class="text-xs" style="color: #1a2232; ">✕</span>
                            </div>

                            <div class="absolute bottom-0 left-0 bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-tr-lg">
                                @{{ i + 1 }}
                            </div>
                        </div>

                        <div v-if="images.length === 0" class="text-gray-400 text-sm italic py-4 w-full text-center">
                            Henüz resim yüklenmedi.
                        </div>

                    </div>

                    <span class="text-xs" style="color: #1a2232; ">Fotoğrafların sırasını düzenleyeebilirsiz.</span>

                    <br />

                    <div class="overflow-hidden border border-zinc-200 rounded-xl bg-white shadow-sm mb-6">
                        <div class="bg-zinc-50 border-b border-zinc-200 px-4 py-3 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-zinc-700">Mezat Özeti ve Onay</h3>
                            <span class="px-2 py-0.5  text-xs text-emerald-500 ">Önizleme</span>
                        </div>

                        <table class="w-full text-left text-sm border-collapse">
                            <tbody class="divide-y divide-zinc-100">
                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-500 w-1/3 bg-zinc-50/30 text-xs uppercase">Müzayede</td>
                                <td class="px-4 py-3 text-zinc-800 font-semibold">@{{ form.title || 'Başlık Girilmedi' }}</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-500 w-1/3 bg-zinc-50/30 text-xs uppercase tracking-wider">Kategori</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1">

                                        <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs  bg-indigo-50 text-indigo-700 border border-indigo-100 ">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>




                                        @{{ selectedCategory?.name || 'Kategori Seçilmedi' }}

                                                            <span v-if="selectedSubCategory" class="text-zinc-400 text-xs">
                                        &nbsp;➜&nbsp; @{{ selectedSubCategory?.name }}
                                    </span></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-500 bg-zinc-50/30 text-xs uppercase">Fiyatlandırma</td>
                                <td class="px-4 py-3 text-zinc-800">
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex ">
                                            <span class="text-sm text-zinc-400 ">Açılış : &nbsp;</span>
                                            <span class="text-sm text-bold text-blue-600"> @{{ form.start_price || 1 }} TL</span>
                                        </div>
                                        <div class="flex  border-l pl-4">
                                            <span class="text-sm text-zinc-400 ">Min. Pey : &nbsp;</span>
                                            <span class="text-sm text-bold text-zinc-700"> @{{ form.min_increment || 1 }} TL</span>
                                        </div>
                                        <div v-if="form.buy_now_price" class="flex border-l pl-4">
                                            <span class="text-sm text-zinc-400 ">Hemen Al : &nbsp;</span>
                                            <span class="text-sm text-bold text-emerald-600"> @{{ form.buy_now_price }} TL</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-500 bg-zinc-50/30 text-xs uppercase">Zamanlama</td>
                                <td class="px-4 py-3 text-zinc-800">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-xs border border-emerald-100">
                                            <span class="opacity-70">Başlangıç:</span>
                                            @{{ form.startImmediately ? 'Yayınlandığında Başlar' : (form.start_at ? formatDisplayDate(form.start_at) : 'Hemen') }}
                                        </div>
                                        <span class="text-zinc-300">➜</span>
                                        <div class="bg-red-50 text-red-700 px-2 py-1 rounded text-xs border border-red-100">
                                            <span class="opacity-70">Bitiş:</span> @{{ form.end_at ? formatDisplayDate(form.end_at) : 'Bitiş Tarihi Seçilmedi' }}
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-500 bg-zinc-50/30 text-xs uppercase">Teslimat & Konum</td>
                                <td class="px-4 py-3 text-zinc-800">
                                    <div v-if="form.delivery_method === 'optional'" class="text-xs italic">
                                        Teslimat bilgisi belirtilmedi (Opsiyonel)
                                    </div>
                                    <div v-else class="space-y-1">
                                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-zinc-200 text-zinc-700 rounded text-[10px] font-bold uppercase">
                                @{{ form.delivery_method === 'pickup' ? 'Yerinde Teslim' : 'Kargo' }}
                            </span>
                                            <span class="font-medium text-zinc-700" v-if="form.city">
                                @{{ form.city }} / @{{ form.district }}
                            </span>
                                        </div>
                                        <p v-if="form.delivery_note" class="text-xs text-zinc-500 italic mt-1 bg-zinc-50 p-2 rounded border border-dashed border-zinc-200">
                                            "@{{ form.delivery_note }}"
                                        </p>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-medium text-zinc-500 bg-zinc-50/30 text-xs uppercase">Detaylar</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                        <span class="text-xs text-zinc-600 truncate max-w-[200px]">
                            @{{ form.description ? (form.description.substring(0, 50) + '...') : 'Açıklama yok' }}
                        </span>
                                        <span class="ml-auto inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 text-blue-700 border border-blue-100">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @{{ images.length }} Görsel
                        </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-6 pt-4">
                        <label class="inline-flex items-center gap-3 cursor-pointer select-none">
        <span class="text-sm font-medium text-zinc-600">
            Girdiğim bilgileri kontrol ettim, onaylıyorum.
        </span>

                            <input
                                type="checkbox"
                                v-model="confirmed"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                            >
                        </label>
                    </div>
                </div>



                <!-- ================= NAV ================= -->



                    <div class="mt-8 border-t pt-6">
                        <div class="flex justify-between items-center">
                            <button
                                @click="prev"
                                :disabled="step === 1"
                                class="px-6 py-2 border rounded-lg text-sm font-medium hover:bg-zinc-50 disabled:opacity-30 transition-all"
                            >
                                ← Önceki
                            </button>

                            <button
                                @click="next"
                                :disabled="isSending || isUploading"
                                class="flex items-center justify-center gap-2 px-6 py-2 rounded-lg border bg-zinc-900 text-white text-sm font-medium border-hover2 transition-all disabled:bg-zinc-400 disabled:cursor-not-allowed min-w-[140px]"
                            >
                                <svg v-if="isSending || isUploading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>

                                <span>
                            <template v-if="isSending">Yayınlanıyor...</template>
                            <template v-else-if="isUploading">Yükleniyor...</template>
                            <template v-else>
                                @{{ step === 4 ? 'Müzayedeyi Başlat 🚀' : 'Sonraki →' }}
                            </template>
                        </span>
                            </button>
                        </div>

                        <div class="h-6 mt-2 flex justify-end">
                            <transition name="fade">
                                <p v-if="errorMessage" class=" text-xs text-red-600 animate-pulse">
                                    ⚠ @{{ errorMessage }}
                                </p>
                            </transition>
                        </div>
                    </div>


            </div>
        </script>




        <style>
            .fade-enter-active, .fade-leave-active {
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            .fade-enter-from, .fade-leave-to {
                opacity: 0;
                transform: translateY(-10px);
            }
            @keyframes slide-down {
                from { opacity: 0; transform: translateY(-5px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-down {
                animation: slide-down 0.2s ease-out;
            }
            /* Grid'i 30 kategori için daha kompakt hale getiriyoruz */
            .truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .searchbox{
                padding-left: 25px;
            }
            .searchboxicon{     padding-left: 10px;}

            .steps li{ cursor:pointer;}
            .steps li:not(:first-child) {

            }

            .stepdisabled{opacity: 0.5;}

            li {
                transition: all 0.5s ease-in-out;
            }

            .pl-7{
                padding-left: 25px;
            }

            .rounded-lg{
                border-radius: 0.2rem !important;
            }

            .rounded-xl{
                border-radius: 0.2rem !important;
            }

            .mb-1{
                margin-bottom: 2px !important;
            }

            .border-hover1:hover{border-color: #e8865b !important;}
            .border-hover2:hover{border-color: #e8865b !important;}
        </style>

        {{-- ================= COMPONENTS ================= --}}
        <script type="module">
            Dropzone.autoDiscover = false;

            app.component('v-dropzone', {
                template: '#v-dropzone-template',
                props: ['uploadUrl'],
                data() {
                    return {
                        dropzone: null,
                        isUploading: false, // Yükleme durumunu takip eder
                        uploadCount: 0
                    }
                },
                mounted() {
                    const self = this;
                    this.dropzone = new Dropzone(this.$refs.dropzone, {
                        url: this.uploadUrl,
                        paramName: 'file',
                        maxFilesize: 5,
                        acceptedFiles: 'image/*',
                        addRemoveLinks: true,
                        autoProcessQueue: true,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        init: function() {
                            // Dropzone içinden bir şey silindiğinde Vue dizisini de güncellemek isterseniz:
                            this.on("removedfile", function(file) {
                                if (file._id) {
                                    self.$emit('removed-manually', file._id);
                                }
                            });

                            this.on("sending", function() {
                                self.uploadCount++;
                                self.updateUploadingStatus();
                            });

                            // Yükleme bittiğinde (başarılı veya başarısız)
                            this.on("complete", function() {
                                self.uploadCount--;
                                self.updateUploadingStatus();
                            });
                        },
                        success: (file, res) => {
                            const id = crypto.randomUUID();
                            file._id = id; // Dropzone dosya objesine ID takıyoruz

                            // Parent'a (Auction Wizard) objeyi gönderiyoruz
                            this.$emit('uploaded', {
                                id: id,
                                url: res.url ?? URL.createObjectURL(file)
                            });
                        },
                        dictDefaultMessage: "Yüklemek için dosyaları buraya sürükleyin",
                        dictFallbackMessage: "Tarayıcınız sürükle-bırak dosya yüklemeyi desteklemiyor.",
                        dictFileTooLarge: "Dosya çok büyük (@{{filesize}}MB). Maksimum limit: @{{maxFilesize}}MB.",
                        dictInvalidFileType: "bu türdeki dosyaları yükleyemezsiniz.",
                        dictResponseError: "Sunucu hatası: @{{statusCode}}",
                        dictCancelUpload: "Yüklemeyi İptal Et",           // <--- Cancel upload
                        dictCancelUploadConfirmation: "Yüklemeyi iptal etmek istediğinize emin misiniz?",
                        dictRemoveFile: "Dosyayı Kaldır",                // <--- Remove file
                        dictMaxFilesExceeded: "Daha fazla dosya yükleyemezsiniz.",
                    });


                },
                methods: {
                    updateUploadingStatus() {
                        this.isUploading = this.uploadCount > 0;
                        // Ana bileşene (Wizard) durumu haber ver
                        this.$emit('status-change', this.isUploading);
                    },
                    clearAllFiles() {
                        if (this.dropzone) {
                            this.dropzone.removeAllFiles(true);
                        }
                    },
                    removeById(id) {
                        // Dropzone içindeki dosyaları tara ve ID'si eşleşeni bul
                        const file = this.dropzone.files.find(f => f._id === id);
                        if (file) {
                            this.dropzone.removeFile(file);
                        }
                    }
                }
            });




            app.component('auction-wizard', {
                template: '#auction-wizard-template',
                data() {
                    return {
                        step: 1,
                        isSubmitted: false,
                        isSending: false,
                        confirmed: false,
                        images: [],

                        durationMode: '1_week',
                        form: {
                            title: '',
                            start_price: '',
                            buy_now_price: '',
                            min_increment: '',
                            start_at: '',
                            end_at: '',
                            description: '',
                            delivery_method: 'optional', // Başlangıçta Opsiyonel seçili gelir
                            city: '',
                            district: '',
                            delivery_note: '',
                            startImmediately: true, // Varsayılan seçili
                        },
                        cities: [], // API'den veya bir dosyadan gelen iller listesi
                        districts: [],

                        searchQuery: '',

                        selectedCategory: null,
                        selectedSubCategory: null,
                        showSuggestionBox: false,  // Metin kutusunun görünürlüğü
                        categorySuggestion: '',    // Kullanıcının yazdığı öneri
                        suggestionSent: false,

                        errorMessage: '',
                        errors: [],
                        categories: @json($auctionCategories ?? [], JSON_UNESCAPED_UNICODE),
                        _legacyCategories: [
                        {
                            "id": 1,
                            "name": "Figür",
                            "icon": "🧸",
                            "subCategories": [
                                { "id": 101, "name": "Anime" },
                                { "id": 102, "name": "Marvel/DC" },
                                { "id": 103, "name": "Lego" },
                                { "id": 104, "name": "Model Araç" }
                            ]
                        },
                        {
                            "id": 2,
                            "name": "Ev Eşyası",
                            "icon": "🏠",
                            "subCategories": [
                                { "id": 201, "name": "Mobilya" },
                                { "id": 202, "name": "Dekorasyon" },
                                { "id": 203, "name": "Aydınlatma" },
                                { "id": 204, "name": "Halı/Kilim" }
                            ]
                        },
                        {
                            "id": 3,
                            "name": "Elektronik",
                            "icon": "📷",
                            "subCategories": [
                                { "id": 301, "name": "Fotoğraf" },
                                { "id": 302, "name": "Ses Sistemleri" },
                                { "id": 303, "name": "Retro Oyun" },
                                { "id": 304, "name": "Telefon" }
                            ]
                        },
                        {
                            "id": 4,
                            "name": "Emlak",
                            "icon": "🏢",
                            "subCategories": [
                                { "id": 401, "name": "Konut" },
                                { "id": 402, "name": "Arsa" },
                                { "id": 403, "name": "Ticari" }
                            ]
                        },
                        {
                            "id": 5,
                            "name": "Araç",
                            "icon": "🚗",
                            "subCategories": [
                                { "id": 501, "name": "Otomobil" },
                                { "id": 502, "name": "Motosiklet" },
                                { "id": 503, "name": "Klasik" }
                            ]
                        },
                        {
                            "id": 6,
                            "name": "Aletler",
                            "icon": "🛠️",
                            "subCategories": [
                                { "id": 601, "name": "El Aletleri" },
                                { "id": 602, "name": "Bahçe" },
                                { "id": 603, "name": "Endüstriyel" }
                            ]
                        },
                        {
                            "id": 7,
                            "name": "Tespih",
                            "icon": "📿",
                            "subCategories": [
                                { "id": 701, "name": "Kehribar" },
                                { "id": 702, "name": "Oltu" },
                                { "id": 703, "name": "Kuka" }
                            ]
                        },
                        {
                            "id": 8,
                            "name": "Sanat",
                            "icon": "🎨",
                            "subCategories": [
                                { "id": 801, "name": "Tablo" },
                                { "id": 802, "name": "Heykel" },
                                { "id": 803, "name": "Hat Sanatı" }
                            ]
                        },
                        {
                            "id": 9,
                            "name": "Saat",
                            "icon": "⌚",
                            "subCategories": [
                                { "id": 901, "name": "Kol Saati" },
                                { "id": 902, "name": "Köstekli" },
                                { "id": 903, "name": "Duvar Saati" }
                            ]
                        },
                        {
                            "id": 10,
                            "name": "Mücevher",
                            "icon": "💎",
                            "subCategories": [
                                { "id": 1001, "name": "Altın" },
                                { "id": 1002, "name": "Gümüş" },
                                { "id": 1003, "name": "Yüzük" }
                            ]
                        },
                        {
                            "id": 11,
                            "name": "Kitap",
                            "icon": "📚",
                            "subCategories": [
                                { "id": 1101, "name": "Nadir Eserler" },
                                { "id": 1102, "name": "İmzalılar" },
                                { "id": 1103, "name": "Dergi" }
                            ]
                        },
                        {
                            "id": 12,
                            "name": "Para/Madalya",
                            "icon": "🪙",
                            "subCategories": [
                                { "id": 1201, "name": "Eski Paralar" },
                                { "id": 1202, "name": "Madalyalar" },
                                { "id": 1203, "name": "Banknot" }
                            ]
                        },
                        {
                            "id": 13,
                            "name": "Müzik",
                            "icon": "🎸",
                            "subCategories": [
                                { "id": 1301, "name": "Enstrüman" },
                                { "id": 1302, "name": "Plak" },
                                { "id": 1303, "name": "CD/Kaset" }
                            ]
                        },
                        {
                            "id": 14,
                            "name": "Spor",
                            "icon": "⚽",
                            "subCategories": [
                                { "id": 1401, "name": "Koleksiyon Formalar" },
                                { "id": 1402, "name": "Ekipman" },
                                { "id": 1403, "name": "Fitness" }
                            ]
                        },
                        {
                            "id": 15,
                            "name": "Giyim",
                            "icon": "👕",
                            "subCategories": [
                                { "id": 1501, "name": "Vintage" },
                                { "id": 1502, "name": "Aksesuar" },
                                { "id": 1503, "name": "Çanta" }
                            ]
                        },
                        {
                            "id": 16,
                            "name": "Hobi",
                            "icon": "🎲",
                            "subCategories": [
                                { "id": 1601, "name": "Masa Oyunları" },
                                { "id": 1602, "name": "Pul/Filateli" },
                                { "id": 1603, "name": "Kartlar & Trading Cards" },
                                { "id": 1604, "name": "Model Araç/Diecast" },
                                { "id": 1605, "name": "Figür & Heykel" },
                                { "id": 1606, "name": "Lego & Yapım Setleri" },
                                { "id": 1607, "name": "Plak & Kaset" },
                                { "id": 1608, "name": "Eski Para/Nümismatik" },
                                { "id": 1609, "name": "Efemerat & Belge" },
                                { "id": 1610, "name": "Maket & Kitler" }
                            ]
                        },
                        {
                            "id": 17,
                            "name": "Efemer",
                            "icon": "✉️",
                            "subCategories": [
                                { "id": 1701, "name": "Kartpostal" },
                                { "id": 1702, "name": "Belge" },
                                { "id": 1703, "name": "Fotoğraflar" }
                            ]
                        },
                        {
                            "id": 18,
                            "name": "Mutfak",
                            "icon": "🍳",
                            "subCategories": [
                                { "id": 1801, "name": "Porselen" },
                                { "id": 1802, "name": "Bakır" },
                                { "id": 1803, "name": "Cam Eşya" }
                            ]
                        },
                        {
                            "id": 19,
                            "name": "Ofis",
                            "icon": "✒️",
                            "subCategories": [
                                { "id": 1901, "name": "Dolma Kalem" },
                                { "id": 1902, "name": "Daktilo" },
                                { "id": 1903, "name": "Kırtasiye" }
                            ]
                        },
                        {
                            "id": 20,
                            "name": "Bahçe",
                            "icon": "🌻",
                            "subCategories": [
                                { "id": 2001, "name": "Bitkiler" },
                                { "id": 2002, "name": "Mobilya" },
                                { "id": 2003, "name": "Heykeller" }
                            ]
                        },
                        {
                            "id": 21,
                            "name": "Diğer",
                            "icon": "📦",
                            "subCategories": [
                                { "id": 2101, "name": "Çeşitli" },
                                { "id": 2102, "name": "Karma Paketler" },
                                { "id": 2103, "name": "Her Şey" }
                            ]
                        }
                    ]


                    };
                },
                    mounted() {
                        this.fetchCities();
                        this.form.start_at = this.getNowForInput();
                    },
                methods: {
                    formatDisplayDate(dateString) {
                        if (!dateString) return 'Belirtilmedi';

                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            weekday: 'long',
                            hour: '2-digit',
                            minute: '2-digit'
                        };

                        const date = new Date(dateString);
                        return new Intl.DateTimeFormat('tr-TR', options).format(date);
                    },
                    prepareSummary() {
                        // 1. Fiyat Kontrolü (Boşsa 1 TL)
                        if (!this.form.start_price || this.form.start_price <= 0) {
                            this.form.start_price = 1;
                        }
                        if (!this.form.min_increment || this.form.min_increment <= 0) {
                            this.form.min_increment = 1;
                        }

                        // 2. Başlangıç Tarihi Kontrolü (Boşsa Şimdi)
                        if (!this.form.start_at) {
                            const now = new Date();
                            this.form.start_at = now.toISOString().slice(0, 16);
                        }

                        // 3. Bitiş Tarihi Hesaplama (DurationMode'a göre)
                        const startDate = new Date(this.form.start_at);
                        if (this.durationMode === '1_week') {
                            startDate.setDate(startDate.getDate() + 7);
                            this.form.end_at = startDate.toISOString().slice(0, 16);
                        } else if (this.durationMode === '1_month') {
                            startDate.setMonth(startDate.getMonth() + 1);
                            this.form.end_at = startDate.toISOString().slice(0, 16);
                        }
                    },
                    clearError(fieldName) {
                        this.errors = this.errors.filter(error => error !== fieldName);
                    },
                    validateForm() {
                        this.errors = []; // Her kontrolde hataları sıfırla
                        const now = new Date();
                        const start = new Date(this.form.start_at);
                        const end = new Date(this.form.end_at);
                        // Title Kontrolü
                        if (!this.form.title || this.form.title.trim() === '') {
                            this.errors.push('title');
                        }

                        if (!this.form.startImmediately) {
                            if (this.form.start_at && start < now.setSeconds(0, 0)) {
                                this.errors.push('start_at_past');
                            }
                        }


                        // 2. Bitiş Tarihi Başlangıç Tarihinden Önce Olamaz
                        if (this.form.start_at && this.form.end_at && end <= start) {
                            this.errors.push('end_at_before_start');
                        }

                        /*
                        if (!this.form.start_price || this.form.start_price <= 0) {
                            this.errors.push('start_price');
                        }*/

                        if (this.form.buy_now_price && this.form.buy_now_price > 0) {
                            const minRequiredBuyNow = Number(this.form.start_price) + Number(this.form.min_increment);

                            if (Number(this.form.buy_now_price) < minRequiredBuyNow) {
                                this.errors.push('buy_now_price_too_low');
                                // Kullanıcıya daha net bir mesaj göstermek istersen bir alert veya toast ekleyebilirsin
                            }
                        }

                        // Eğer Teslimat "Opsiyonel" değilse İl ve İlçe zorunlu olsun
                        if (this.form.delivery_method !== 'optional') {
                            if (!this.form.city) this.errors.push('city');
                            if (!this.form.district) this.errors.push('district');
                        }

                        // Hata varsa false dön, yoksa true
                        return this.errors.length === 0;
                    },
                    async fetchCities() {
                        try {
                            const response = await fetch('https://api.turkiyeapi.dev/v1/provinces');
                            const result = await response.json();
                            // API'den gelen "data" dizisini alfabetik sıralayarak kaydediyoruz
                            this.cities = result.data.sort((a, b) => a.name.localeCompare(b.name));
                        } catch (error) {
                            console.error('İller yüklenirken hata oluştu:', error);
                        }
                    },
                    handleCityChange() {
                        this.form.district = ''; // İl değişince ilçeyi sıfırla

                        // Seçilen il ismine göre cities dizisinden ilgili ili bul
                        const selectedCity = this.cities.find(c => c.name === this.form.city);

                        if (selectedCity && selectedCity.districts) {
                            // İlin içindeki hazır ilçe listesini al ve alfabetik sırala
                            this.districts = [...selectedCity.districts].sort((a, b) =>
                                a.name.localeCompare(b.name)
                            );
                        } else {
                            this.districts = [];
                        }
                    },

                    updateEndDate() {
                        let start = this.startImmediately ? new Date() : new Date(this.form.start_at);
                        if (isNaN(start.getTime())) start = new Date();

                        let end = new Date(start);

                        if (this.durationMode === '1_week') {
                            end.setDate(end.getDate() + 7);
                        } else if (this.durationMode === '1_month') {
                            end.setMonth(end.getMonth() + 1);
                        }

                        // datetime-local formatına çevir (YYYY-MM-DDThh:mm)
                        this.form.end_at = end.toLocaleString('sv-SE').replace(' ', 'T').slice(0, 16);
                    },
                    selectOtherCategory() {
                        const otherCat = this.categories.find(c => c.name === 'Diğer');
                        if (otherCat) {
                            this.selectedCategory = otherCat;
                            this.selectedSubCategory = otherCat.subCategories[2];

                            document.getElementById('progressBar').scrollIntoView({ behavior: 'smooth' });
                        }
                    },

                    // Öneriyi gönderen metot
                    submitSuggestion() {
                        if (!this.categorySuggestion.trim()) return;

                        console.log('Kategori Önerisi:', this.categorySuggestion);


                        this.suggestionSent = true;
                        this.categorySuggestion = '';

                        // 3 saniye sonra kutuyu kapat ve mesajı sıfırla
                        setTimeout(() => {
                            this.showSuggestionBox = false;
                            this.suggestionSent = false;
                        }, 3000);
                    },
                    removeImage(index) {
                        const removedItem = this.images[index];

                        // 1. Vue dizisinden sil
                        this.images.splice(index, 1);

                        // 2. Dropzone'dan sil (ref kullanarak alt bileşene komut gönderiyoruz)
                        if (this.$refs.dropzoneRef) {
                            this.$refs.dropzoneRef.removeById(removedItem.id);
                        }
                    },

                    // SIRALAMA BAŞLATMA
                    initSortable() {


                        this.$nextTick(() => {
                            const el = document.getElementById('image-grid');

                            if (!el || this.images.length === 0) return;

                            if (el.sortable) return;

                            Sortable.create(el, {
                                animation: 150,
                                ghostClass: 'opacity-40',
                                onEnd: (evt) => {
                                    const item = this.images.splice(evt.oldIndex, 1)[0];
                                    this.images.splice(evt.newIndex, 0, item);
                                }
                            });
                        });
                    },
                    formatDisplay(value) {
                        if (!value && value !== 0) return '';
                        return new Intl.NumberFormat('tr-TR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(value);
                    },

                    numberToWords(n) {
                        if (n === 0) return "sıfır";
                        const birler = ["", "bir", "iki", "üç", "dört", "beş", "altı", "yedi", "sekiz", "dokuz"];
                        const onlar = ["", "on", "yirmi", "otuz", "kırk", "elli", "altmış", "yetmiş", "seksen", "doksan"];
                        const binler = ["", "bin", "milyon", "milyar"];

                        let word = "";
                        let step = 0;

                        while (n > 0) {
                            let chunk = n % 1000;
                            if (chunk > 0) {
                                let s = "";
                                // Yüzler
                                if (Math.floor(chunk / 100) > 0) {
                                    s += (Math.floor(chunk / 100) === 1 ? "" : birler[Math.floor(chunk / 100)]) + "yüz";
                                }
                                // Onlar ve Birler
                                let rem = chunk % 100;
                                if (rem >= 10) {
                                    s += onlar[Math.floor(rem / 10)] + birler[rem % 10];
                                } else {
                                    s += birler[rem];
                                }

                                // Binler basamağı özel kontrolü (1 bin denmez, sadece bin denir)
                                if (step === 1 && chunk === 1) {
                                    word = "bin" + word;
                                } else {
                                    word = s + binler[step] + word;
                                }
                            }
                            n = Math.floor(n / 1000);
                            step++;
                        }
                        return word + " lira";
                    },

                    // Kuruşsuz güncelleme (Sadece tam sayı)
                    updatePrice(event, field) {
                        let inputVal = event.target.value;
                        // Sadece rakamları al, kuruş hanesiyle uğraşma
                        let numericValue = inputVal.replace(/\D/g, '');
                        let val = parseInt(numericValue) || 0;

                        this.form[field] = val;
                        event.target.value = new Intl.NumberFormat('tr-TR').format(val);
                    },
                    calculateEndDate() {
                        // Eğer başlangıç tarihi yoksa veya manuel tarih seçimi modundaysa hesaplama yapma
                        if (!this.form.start_at) return;

                        // JavaScript Date objesi oluştur
                        let startDate = new Date(this.form.start_at);
                        let endDate = new Date(this.form.start_at);

                        if (this.durationMode === '1_week') {
                            // 7 gün ekle
                            endDate.setDate(startDate.getDate() + 7);
                        }
                        else if (this.durationMode === '1_month') {
                            // 1 ay ekle
                            endDate.setMonth(startDate.getMonth() + 1);
                        }
                        else {
                            // Eğer manuel bir seçim varsa veya süre seçilmemişse fonksiyondan çık
                            return;
                        }

                        // ISO formatına çevir (input[type="datetime-local"] formatı: YYYY-MM-DDTHH:mm)
                        // Intl.DateTimeFormat veya basit string manipülasyonu kullanılabilir
                        this.form.end_at = this.formatToDateTimeLocal(endDate);
                    },


                    formatToDateTimeLocal(date) {
                        const pad = (num) => String(num).padStart(2, '0');

                        const year = date.getFullYear();
                        const month = pad(date.getMonth() + 1);
                        const day = pad(date.getDate());
                        const hours = pad(date.getHours());
                        const minutes = pad(date.getMinutes());

                        return `${year}-${month}-${day}T${hours}:${minutes}`;
                    },
                    next() {

                        if (this.step === 1) {
                            if (!this.selectedCategory || !this.selectedSubCategory) {
                                this.showError('Devam etmek için bir  kategori seçmelisiniz.');
                                return;
                            }
                        }

                        if (this.step === 2) {

                        }

                        if (this.step === 3 && !this.form.title) {
                            this.errors.push('title');
                            this.showError('Devam etmek için ilgili alanları doldurun.');
                            return;
                        }

                        if (this.step === 3 ) {
                            if (!this.validateForm()) {
                                if (this.errors.includes('buy_now_price_too_low')) {
                                    this.showError('Hemen al fiyatı hatalı. Açılış fiyatı ve minimum pey toplamından düşük olamaz.');
                                }
                                else if(!this.form.startImmediately && this.errors.includes('start_at_past')) {
                                    this.showError('Başlagıç tarihi bugünde önce olamaz.');

                                }
                                else if(this.errors.includes('end_at_before_start')) {
                                    this.showError('Bitiş tarihi başlagıç tarihiden öce olamaz.');

                                }
                                else {
                                    this.showError('Lütfen formdaki zorunlu ve hatalı alanları kontrol ediniz.');
                                }
                                return;}

                        }


                        if (this.step === 4) {

                            if (!this.confirmed) {
                                this.showError('Lütfen onay kutusunu işaretleyin.');
                                return;
                            }
                            this.submit();
                            return;
                        }

                        this.refreshPreview();

                        if (this.$refs.dropzoneRef) {
                            // v-dropzone bileşeninin içindeki metodu tetikler
                            this.$refs.dropzoneRef.clearAllFiles();
                        }

                        this.step++;

                        const nextStepHeader = document.getElementById(`step-header-${this.step}`);
                        if (nextStepHeader) {
                            nextStepHeader.classList.remove('stepdisabled');
                            // İstersen ek efektler de verebilirsin
                            nextStepHeader.style.opacity = "1";
                            nextStepHeader.style.transition = "all 0.5s ease";
                        }
                    },
                    getNowForInput() {
                        const now = new Date();
                        now.setMinutes(now.getMinutes() + 15);
                        now.setSeconds(0);
                        now.setMilliseconds(0);

                        return this.formatToDateTimeLocal(now);
                    },
                    refreshPreview() {
                        console.log("Önizleme verileri yenileniyor...");

                        // 1. Fiyatları kontrol et (Boşsa 1 TL yap)
                        if (!this.form.start_price || this.form.start_price <= 0) {
                            this.form.start_price = 1;
                        }
                        if (!this.form.min_increment || this.form.min_increment <= 0) {
                            this.form.min_increment = 1;
                        }

                        // 2. Tarihleri tazele
                        // Eğer başlangıç tarihi seçilmemişse "Şimdi"yi ata
                        if (!this.form.start_at) {
                            const now = new Date();
                            this.form.start_at = now.toISOString().slice(0, 16);
                        }

                        // DurationMode'a göre bitişi hesapla (Eğer manuel seçilmediyse)
                        this.calculateEndDate();

                        // 3. Reaktifliği zorlamak için objeyi "clone"layabilirsin (Opsiyonel)
                        this.form = { ...this.form };
                    },
                    prev() {

                        if (this.step > 1) {
                            this.step--;
                            this.confirmed = false;
                        }

                    },
                    showError(msg) {
                        this.errorMessage = msg;
                        // Kullanıcı art arda basarsa timer'ı sıfırlamak iyidir
                        if (this.errorTimeout) clearTimeout(this.errorTimeout);
                        this.errorTimeout = setTimeout(() => {
                            this.errorMessage = '';
                        }, 4000);
                    },
                    submit() {
                        // 1. Validasyon Kontrolü
                        if (!this.validateForm()) {
                            alert("Lütfen formdaki hatalı veya eksik alanları kontrol edin.");
                            return;
                        }

                        // 2. Çift gönderimi engelle (Guard Clause)
                        if (this.isSending) return;

                        // Gönderim başladı
                        this.isSending = true;

                        const storeUrl = "{{ route('shop.auction.store') }}";
                        const payload = {
                            ...this.form,
                            start_at: this.form.startImmediately ? this.getNowForInput() : this.form.start_at,
                            images: this.images.map(img => img.url),
                            category_id: this.selectedSubCategory?.id || this.selectedCategory?.id || null,
                            category_parent_id: this.selectedCategory?.id || null,
                            category_name: this.selectedSubCategory?.name || this.selectedCategory?.name || null,
                            category_suggestion: this.categorySuggestion || null
                        };

                        axios.post(storeUrl, payload)
                            .then(res => {
                                if (res.data.success || res.status === 200) {
                                    this.isSubmitted = true;

                                    // DOM Manipülasyonları
                                    const steps = document.getElementById('stepsSection');
                                    if (steps) steps.classList.add('hidden');

                                    const formContainer = document.getElementById('auctionFormContainer');
                                    if (formContainer) formContainer.classList.add('hidden');

                                    const successSection = document.getElementById('successResultSection');
                                    if (successSection) successSection.classList.remove('hidden');

                                    window.scrollTo({ top: 0, behavior: 'smooth' });
                                }
                            })
                            .catch(err => {
                                console.error('Hata Detayı:', err.response);
                                const serverErrors = err.response?.data?.errors
                                    ? Object.values(err.response.data.errors).flat().join('\n')
                                    : 'Bir hata oluştu.';
                                alert("Hata:\n" + serverErrors);

                                // Hata durumunda butonu tekrar aktif et ki kullanıcı düzeltebilsin
                                this.isSending = false;
                            })
                        // Başarılı olursa isSending false yapmaya gerek yok çünkü sayfa/div değişiyor
                    },
                    toggleCategory(cat) {
                        if (this.selectedCategory?.id === cat.id) {
                            this.selectedCategory = null;
                            this.selectedSubCategory = null;
                        } else {
                            this.selectedCategory = cat;
                            this.selectedSubCategory = null; // Ana kategori değişince alt seçimi sıfırla
                        }
                    },
                    selectSub(sub) {
                        this.selectedSubCategory = sub;
                    }
                },
                computed: {
                    filteredCategories() {
                        if (!this.searchQuery) return this.categories;

                        const query = this.searchQuery.toLowerCase();

                        return this.categories.filter(cat => {
                            // Ana kategori isminde var mı?
                            const matchMain = cat.name.toLowerCase().includes(query);

                            // Alt kategorilerde var mı?
                            const matchSub = cat.subCategories.some(sub =>
                                sub.name.toLowerCase().includes(query)
                            );

                            return matchMain || matchSub;
                        });
                    },
                    progressStyle() {
                        return {
                            transform: this.step === 1
                                ? 'translateX(0%)'
                                : this.step === 2
                                    ? 'translateX(100%)'
                                : this.step === 3
                                    ? 'translateX(200%)'
                                    : 'translateX(300%)'
                        };
                    }

                },
                watch: {
                    startImmediately(val) {
                        if (val) {
                            this.form.start_at = new Date().toISOString().slice(0, 16); // Şu anki zamanı set et
                            this.updateEndDate(); // Bitişi de güncelle
                        }
                    },
                    // Bitiş modunu izle
                    durationMode(val) {
                        if (val !== 'manual') {
                            this.updateEndDate();
                        }
                    },
                    searchQuery(newVal) {
                        if (newVal.length > 1) { // En az 2 karakter girilince otomatik açma başlasın
                            const results = this.filteredCategories;
                            if (results.length === 1) {
                                this.selectedCategory = results[0];
                            }
                        }
                    },

                    step(newStep) {
                        if (newStep === 4) {
                            this.initSortable();
                        }
                    },


                    images: {
                        handler(newImages) {
                            if (this.step === 4 && newImages.length > 0) {
                                this.initSortable();
                            }
                        },
                        deep: true
                    },

                    'form.start_price': function (newVal) {
                        if (newVal && newVal > 0) {
                            // 1. ADIM: Önce %10'unu hesapla (Örn: 250 -> 25)
                            const percentTen = newVal / 10;

                            // 2. ADIM: Çıkan sonucu 25'in katına yuvarla
                            // (25 / 25 = 1 -> 1 * 25 = 25)
                            let calculatedMin = Math.round(percentTen / 25) * 25;

                            // 3. ADIM: Eğer sayı çok küçükse (Örn: 100 girdi, %10'u 10 eder, 25'e yuvarlayınca 0 olur)
                            // Alt sınır belirle (En az 10 veya 25 olsun gibi)
                            if (calculatedMin === 0) {
                                calculatedMin = 10;
                            }

                            this.form.min_increment = calculatedMin;
                        } else {
                            this.form.min_increment = 0;
                        }
                    }
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
