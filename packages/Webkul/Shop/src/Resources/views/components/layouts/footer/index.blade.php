{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer class="mt-12 bg-white border-t border-zinc-200">
    <div class="max-w-[1440px] mx-auto px-[60px] py-16 max-1060:p-8 max-sm:px-4 max-sm:py-8">
        <div class="grid grid-cols-12 gap-10">



            <div class="col-span-4 max-1060:hidden">
                <div class="flex justify-between gap-8">
                    @if ($customization?->options)
                        @foreach ($customization->options as $footerLinkSection)
                            <div class="flex flex-col gap-5">
                                <h4 class="text-zinc-900 font-bold uppercase text-xs tracking-widest">Kurumsal</h4>
                                <ul class="grid gap-3 text-sm">
                                    @php
                                        usort($footerLinkSection, function ($a, $b) {
                                            return $a['sort_order'] - $b['sort_order'];
                                        });
                                    @endphp

                                    @foreach ($footerLinkSection as $link)
                                        <li>
                                            <a href="{{ $link['url'] }}" class="text-zinc-500 hover:text-zinc-900 transition-colors">
                                                {{ $link['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-span-4 max-1060:col-span-12 bg-zinc-50 p-8 rounded-2xl border border-zinc-100">
                <div class="grid gap-4">
                    <h4 class="text-xl font-bold text-zinc-900">Müzayedeleri Kaçırmayın</h4>
                    <p class="text-sm text-zinc-500">
                        Yeni eklenen eserlerden ve yaklaşan canlı mezatlardan ilk siz haberdar olun.
                    </p>

                    <x-shop::form :action="route('shop.subscription.store')">
                        <div class="relative mt-2">
                            <x-shop::form.control-group.control
                                type="email"
                                class="w-full rounded-xl border-zinc-200 bg-white px-4 py-4 pr-32 text-sm focus:ring-2 focus:ring-zinc-900 outline-none"
                                name="email"
                                rules="required|email"
                                placeholder="E-posta adresiniz"
                            />
                            <button
                                type="submit"
                                class="absolute right-1.5 top-1.5 rounded-lg bg-zinc-900 px-5 py-2.5 text-xs font-bold text-white hover:bg-zinc-800 transition-all"
                            >
                                Kayıt Ol
                            </button>
                        </div>
                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form>
                </div>
            </div>
        </div>

        <div class="hidden max-1060:block mt-10">
            <x-shop::accordion :is-active="false">
                <x-slot:header class="py-4 font-bold text-zinc-900 border-b border-zinc-100">
                    Hızlı Bağlantılar
                </x-slot>
                <x-slot:content class="py-4">
                </x-slot>
            </x-shop::accordion>
        </div>
    </div>

    <div class="border-t border-zinc-100 bg-zinc-50 px-[60px] py-6 max-sm:px-4">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center max-md:flex-col max-md:gap-6">
            <p class="text-xs text-zinc-500">
                © {{ date('Y') }} Mezat A.Ş. Tüm Hakları Saklıdır.
            </p>

            <div class="flex items-center gap-6 opacity-60 grayscale hover:grayscale-0 transition-all">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-4">
                <div class="h-6 w-[1px] bg-zinc-300"></div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-zinc-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path></svg>
                    <span class="text-[10px] font-bold text-zinc-600 uppercase tracking-tighter">256-Bit SSL Güvenli Ödeme</span>
                </div>
            </div>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
