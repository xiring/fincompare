<?php

namespace Src\Shared\Presentation\Controllers\Public;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;
use Src\Partners\Domain\Entities\Partner;

/**
 * FrontendController controller.
 */
class FrontendController extends Controller
{
    public function __construct(private readonly FaqRepositoryInterface $faqsRepo) {}

    /**
     * Handle Home.
     */
    public function home(): Factory|View|\Illuminate\View\View
    {
        // Fetch featured products (is_featured = true and status = active) - limit to 3
        $featuredProducts = Product::with(['partner', 'productCategory'])
            ->where('is_featured', true)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Fetch categories for the category section
        $categories = ProductCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Fetch partners for the partners section
        $partners = Partner::where('status', 'active')
            ->orderBy('name')
            ->limit(10)
            ->get();

        return view('public.home', compact('featuredProducts', 'categories', 'partners'));
    }

    /**
     * Handle About.
     */
    public function about(): Application|Factory|View|\Illuminate\View\View
    {
        return view('public.about');
    }

    /**
     * Handle Privacy.
     */
    public function privacy(): Factory|View|\Illuminate\View\View
    {
        return view('public.privacy');
    }

    /**
     * Handle Terms.
     */
    public function terms(): Factory|View|\Illuminate\View\View
    {
        return view('public.terms');
    }

    /**
     * Handle Contact.
     */
    public function contact(): Factory|View|\Illuminate\View\View
    {
        return view('public.contact');
    }

    /**
     * Handle Faq.
     */
    public function faq(): Factory|View|\Illuminate\View\View
    {
        $faqs = $this->faqsRepo->list(['sort' => 'created_at', 'dir' => 'asc']);

        return view('public.faq', compact('faqs'));
    }
}
