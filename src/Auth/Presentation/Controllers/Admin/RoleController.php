<?php

namespace Src\Auth\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Auth\Application\Actions\CreateRoleAction;
use Src\Auth\Application\Actions\DeleteRoleAction;
use Src\Auth\Application\Actions\ListRolesAction;
use Src\Auth\Application\Actions\UpdateRoleAction;
use Src\Auth\Application\DTOs\RoleDTO;

/**
 * RoleController controller.
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
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        return response()->json(['message' => 'Provide role payload to store.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\Src\Auth\Presentation\Requests\RoleRequest $request, CreateRoleAction $create)
    {
        $role = $create->execute(RoleDTO::fromArray($request->validated()));

        return response()->json($role->load('permissions'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('view', $role);
        return response()->json($role->load('permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);
        return response()->json($role->load('permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\Src\Auth\Presentation\Requests\RoleRequest $request, int $id, UpdateRoleAction $update)
    {
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);
        $role = $update->execute($role, RoleDTO::fromArray($request->validated()));

        return response()->json($role->load('permissions'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, DeleteRoleAction $delete)
    {
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role);
        $delete->execute($role);

        return response()->json(null, 204);
    }
}
