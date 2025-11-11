<?php
namespace Src\Auth\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Src\Auth\Application\Actions\ListRolesAction;
use Src\Auth\Application\Actions\CreateRoleAction;
use Src\Auth\Application\Actions\UpdateRoleAction;
use Src\Auth\Application\Actions\DeleteRoleAction;
use Src\Auth\Application\DTOs\RoleDTO;

/**
 * RoleController controller.
 *
 * @package Src\Auth\Presentation\Controllers\Admin
 */
class RoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListRolesAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir')
        ], (int)$request->get('per_page',20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.roles.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message'=>'Provide role payload to store.']);
        $permissions = Permission::orderBy('name')->get(['id','name']);
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\Src\Auth\Presentation\Requests\RoleRequest $request, CreateRoleAction $create)
    {
        $role = $create->execute(RoleDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($role->load('permissions'),201);
        return redirect()->route('admin.roles.index')->with('status','Role created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Role $role)
    {
        if ($request->wantsJson()) return response()->json($role->load('permissions'));
        $permissions = Permission::orderBy('name')->get(['id','name']);
        return view('admin.roles.edit', compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\Src\Auth\Presentation\Requests\RoleRequest $request, Role $role, UpdateRoleAction $update)
    {
        $role = $update->execute($role, RoleDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($role->load('permissions'));
        return redirect()->route('admin.roles.index')->with('status','Role updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Role $role, DeleteRoleAction $delete)
    {
        $delete->execute($role);
        if ($request->wantsJson()) return response()->json(null,204);
        return back()->with('status','Role deleted');
    }
}


