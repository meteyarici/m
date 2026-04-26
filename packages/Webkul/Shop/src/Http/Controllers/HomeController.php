<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Requests\ContactRequest;
use Webkul\Shop\Http\Resources\CategoryTreeResource;
use Webkul\Shop\Mail\ContactUs;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class HomeController extends Controller
{
    /**
     * Using const variable for status
     */
    const STATUS = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ThemeCustomizationRepository $themeCustomizationRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository) {}

    /**
     * Loads the home page for the storefront.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        visitor()->visit();

        $customizations = $this->themeCustomizationRepository->orderBy('sort_order')->findWhere([
            'status'     => self::STATUS,
            'channel_id' => core()->getCurrentChannel()->id,
            'theme_code' => core()->getCurrentChannel()->theme,
        ]);

        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        $categories = CategoryTreeResource::collection($categories);

        return view('shop::home.index', compact('customizations', 'categories'));
    }

    /**
     * Loads the home page for the storefront if something wrong.
     *
     * @return \Exception
     */
    public function notFound()
    {
        abort(404);
    }

    /**
     * Summary of contact.
     *
     * @return \Illuminate\View\View
     */
    public function contactUs()
    {
        return view('shop::home.contact-us');
    }

    /**
     * Summary of store.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContactUsMail(ContactRequest $contactRequest)
    {
        try {
            Mail::queue(new ContactUs($contactRequest->only([
                'name',
                'email',
                'contact',
                'message',
            ])));

            session()->flash('success', trans('shop::app.home.thanks-for-contact'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            report($e);
        }

        return back();
    }

    public function createAuction()
    {
        if (! auth()->guard('customer')->check()) {
            Cookie::queue('return_to', route('shop.create-auction.index'), 10);
            return redirect()->route('shop.customer.session.index');
        }

        $rootId = (int) (core()->getCurrentChannel()->root_category_id ?? 1);

        $locale = core()->getCurrentLocale()->code ?? 'tr';

        $mains = DB::table('categories as c')
            ->join('category_translations as t', function ($join) use ($locale) {
                $join->on('t.category_id', '=', 'c.id')->where('t.locale', $locale);
            })
            ->where('c.parent_id', $rootId)
            ->where('c.status', 1)
            ->orderBy('c.position')
            ->get(['c.id', 'c.additional', 't.name']);

        $auctionCategories = $mains->map(function ($main) use ($locale) {
            $additional = json_decode($main->additional ?? '', true) ?: [];

            $subs = DB::table('categories as c')
                ->join('category_translations as t', function ($join) use ($locale) {
                    $join->on('t.category_id', '=', 'c.id')->where('t.locale', $locale);
                })
                ->where('c.parent_id', $main->id)
                ->where('c.status', 1)
                ->orderBy('c.position')
                ->get(['c.id', 't.name']);

            return [
                'id'            => (int) $main->id,
                'name'          => $main->name,
                'icon'          => $additional['icon'] ?? '',
                'subCategories' => $subs->map(fn ($s) => [
                    'id'   => (int) $s->id,
                    'name' => $s->name,
                ])->values()->all(),
            ];
        })->values()->all();

        return view('shop::home.create-auction', compact('auctionCategories'));
    }
}
