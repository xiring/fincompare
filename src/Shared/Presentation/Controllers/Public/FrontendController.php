<?php

namespace Src\Shared\Presentation\Controllers\Public;

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
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function home()
    {
        return view('Shared.Presentation.Views.Public.home');
    }

    /**
     * Handle About.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function about()
    {
        return view('Shared.Presentation.Views.Public.about');
    }

    /**
     * Handle Privacy.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function privacy()
    {
        return view('Shared.Presentation.Views.Public.privacy');
    }

    /**
     * Handle Terms.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function terms()
    {
        return view('Shared.Presentation.Views.Public.terms');
    }

    /**
     * Handle Contact.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function contact()
    {
        return view('Shared.Presentation.Views.Public.contact');
    }

    /**
     * Handle Faq.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function faq(FaqRepositoryInterface $faqsRepo)
    {
        $faqs = $this->faqsRepo->list(['sort' => 'created_at', 'dir' => 'asc']);

        return view('Shared.Presentation.Views.Public.faq', compact('faqs'));
    }
}
