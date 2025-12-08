<?php

namespace Src\Partners\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Src\Partners\Application\Actions\CreatePartnerAction;
use Src\Partners\Application\Actions\DeletePartnerAction;
use Src\Partners\Application\Actions\ListPartnersAction;
use Src\Partners\Application\Actions\ShowPartnerAction;
use Src\Partners\Application\Actions\UpdatePartnerAction;
use Src\Partners\Application\DTOs\PartnerDTO;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Presentation\Requests\PartnerRequest;

/**
 * PartnerController controller.
 */
class PartnerController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Partner::class, 'partner');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListPartnersAction $list)
    {
        $partners = $list->execute([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));

        return response()->json($partners);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        return response()->json(['message' => 'Provide partner payload to store.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PartnerRequest $request, CreatePartnerAction $create)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('partners/'.now()->format('Y/m'), 'public');
        }

        $partner = $create->execute(PartnerDTO::fromArray($data));

        return response()->json($partner, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Partner $partner, ShowPartnerAction $show)
    {
        $partner = $show->execute($partner);

        return response()->json($partner);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Partner $partner, ShowPartnerAction $show)
    {
        $partner = $show->execute($partner);

        return response()->json($partner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PartnerRequest $request, Partner $partner, UpdatePartnerAction $update)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($partner->logo_path) {
                Storage::disk('public')->delete($partner->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('partners/'.now()->format('Y/m'), 'public');
        } else {
            $data['logo_path'] = $partner->logo_path; // Keep existing logo if not updated
        }

        $partner = $update->execute($partner, PartnerDTO::fromArray($data));

        return response()->json($partner);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Partner $partner, DeletePartnerAction $delete)
    {
        $delete->execute($partner);

        return response()->json(null, 204);
    }
}
