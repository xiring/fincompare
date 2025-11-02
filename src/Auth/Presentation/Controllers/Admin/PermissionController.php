<?php
namespace Src\Auth\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Permission;
use Src\Auth\Application\Actions\ListPermissionsAction;
use Src\Auth\Application\Actions\CreatePermissionAction;
use Src\Auth\Application\Actions\UpdatePermissionAction;
use Src\Auth\Application\Actions\DeletePermissionAction;
use Src\Auth\Application\DTOs\PermissionDTO;

class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index(Request $request, ListPermissionsAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir')
        ], (int)$request->get('per_page',20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.permissions.index', compact('items'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message'=>'Provide permission payload to store.']);
        return view('admin.permissions.create');
    }

    public function store(\Src\Auth\Presentation\Requests\PermissionRequest $request, CreatePermissionAction $create)
    {
        $perm = $create->execute(PermissionDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($perm,201);
        return redirect()->route('admin.permissions.index')->with('status','Permission created');
    }

    public function edit(Request $request, Permission $permission)
    {
        if ($request->wantsJson()) return response()->json($permission);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(\Src\Auth\Presentation\Requests\PermissionRequest $request, Permission $permission, UpdatePermissionAction $update)
    {
        $permission = $update->execute($permission, PermissionDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($permission);
        return redirect()->route('admin.permissions.index')->with('status','Permission updated');
    }

    public function destroy(Request $request, Permission $permission, DeletePermissionAction $delete)
    {
        $delete->execute($permission);
        if ($request->wantsJson()) return response()->json(null,204);
        return back()->with('status','Permission deleted');
    }
}


