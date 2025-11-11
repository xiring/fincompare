<?php
namespace Src\Shared\Presentation\Controllers\Public;

use Illuminate\Routing\Controller;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;

class FrontendController extends Controller
{
    public function __construct(private readonly FaqRepositoryInterface $faqsRepo) {

    }

    public function home()
    {
        return view('Shared.Presentation.Views.Public.home');
    }

    public function about()
    {
        return view('Shared.Presentation.Views.Public.about');
    }

    public function privacy()
    {
        return view('Shared.Presentation.Views.Public.privacy');
    }

    public function terms()
    {
        return view('Shared.Presentation.Views.Public.terms');
    }

    public function contact()
    {
        return view('Shared.Presentation.Views.Public.contact');
    }

    public function faq(FaqRepositoryInterface $faqsRepo)
    {
        $faqs = $this->faqsRepo->list(['sort' => 'created_at', 'dir' => 'asc']);
        return view('Shared.Presentation.Views.Public.faq', compact('faqs'));
    }
}


