<?php
namespace Src\Partners\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Presentation\Requests\PartnerRequest;
use Src\Partners\Application\Actions\ListPartnersAction;
use Src\Partners\Application\Actions\ShowPartnerAction;
use Src\Partners\Application\Actions\CreatePartnerAction;
use Src\Partners\Application\Actions\UpdatePartnerAction;
use Src\Partners\Application\Actions\DeletePartnerAction;
use Src\Partners\Application\DTOs\PartnerDTO;

class PartnerController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Partner::class, 'partner');
    }

    public function index(Request $request, ListPartnersAction $list)
    {
        $partners = $list->execute([
            'q'=>$request->get('q'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir')
        ], (int)$request->get('per_page', 20));
        if ($request->wantsJson()) return response()->json($partners);
        return view('admin.partners.index', compact('partners'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message' => 'Provide partner payload to store.']);
        return view('admin.partners.create');
    }

    public function store(PartnerRequest $request, CreatePartnerAction $create)
    {
        $partner = $create->execute(PartnerDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($partner, 201);
        return redirect()->route('admin.partners.index')->with('status', 'Partner created');
    }

    public function show(Request $request, Partner $partner, ShowPartnerAction $show)
    {
        $partner = $show->execute($partner);
        if ($request->wantsJson()) return response()->json($partner);
        return view('admin.partners.edit', compact('partner'));
    }

    public function edit(Request $request, Partner $partner, ShowPartnerAction $show)
    {
        $partner = $show->execute($partner);
        if ($request->wantsJson()) return response()->json($partner);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(PartnerRequest $request, Partner $partner, UpdatePartnerAction $update)
    {
        $partner = $update->execute($partner, PartnerDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($partner);
        return redirect()->route('admin.partners.index')->with('status', 'Partner updated');
    }

    public function destroy(Request $request, Partner $partner, DeletePartnerAction $delete)
    {
        $delete->execute($partner);
        if ($request->wantsJson()) return response()->json(null, 204);
        return redirect()->route('admin.partners.index')->with('status', 'Partner deleted');
    }
}


