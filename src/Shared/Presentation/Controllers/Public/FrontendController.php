<?php

namespace Src\Shared\Presentation\Controllers\Public;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;

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
        return view('public.home');
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
