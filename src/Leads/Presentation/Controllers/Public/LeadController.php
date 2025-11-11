<?php
namespace Src\Leads\Presentation\Controllers\Public;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Src\Leads\Application\Actions\CaptureLeadAction;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Catalog\Domain\Entities\Product;

/**
 * LeadController controller.
 *
 * @package Src\Leads\Presentation\Controllers\Public
 */
class LeadController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function create(Request $request)
    {
        $product = null;
        if ($id = $request->get('product')) {
            $product = Product::find($id);
        }
        return view()->file(base_path('src/Leads/Presentation/Views/Public/lead_form.blade.php'), compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, CaptureLeadAction $capture)
    {
        $data = $request->validate([
            'product_id' => ['nullable','integer','exists:products,id'],
            'full_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'phone' => ['required','string','max:50'],
            'city' => ['nullable','string','max:120'],
            'message' => ['nullable','string','max:2000'],
        ]);

        $dto = new LeadDTO(
            product_id: $data['product_id'] ?? null,
            full_name: $data['full_name'] ?? null,
            email: $data['email'] ?? null,
            mobile_number: $data['phone'] ?? null,
            message: $data['message'] ?? null,
            source: 'web',
            status: 'new',
            meta: ['city'=>$data['city'] ?? null]
        );

        $lead = $capture->execute($dto);
        return redirect('/')->with('status','Thanks! Your inquiry has been received.');
    }
}


