<?php

namespace Src\Leads\Presentation\Controllers\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Entities\Product;
use Src\Leads\Application\Actions\CaptureLeadAction;
use Src\Leads\Application\DTOs\LeadDTO;

/**
 * LeadController controller.
 */
class LeadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, CaptureLeadAction $capture)
    {
        $data = $request->validate([
            'product_id' => ['nullable'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        // Resolve product_id from slug or ID
        $productId = null;
        if (!empty($data['product_id'])) {
            $productParam = $data['product_id'];
            // If it's numeric, treat as ID; otherwise treat as slug
            if (is_numeric($productParam)) {
                $product = Product::find($productParam);
            } else {
                $product = Product::where('slug', $productParam)->first();
            }
            $productId = $product?->id;
        }

        $dto = new LeadDTO(
            product_id: $productId,
            full_name: $data['full_name'] ?? null,
            email: $data['email'] ?? null,
            mobile_number: $data['phone'] ?? null,
            message: $data['message'] ?? null,
            source: 'web',
            status: 'new',
            meta: ['city' => $data['city'] ?? null]
        );

        $lead = $capture->execute($dto);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Thanks! Your inquiry has been received.',
                'status' => 'success'
            ]);
        }

        return redirect('/')->with('status', 'Thanks! Your inquiry has been received.');
    }
}
