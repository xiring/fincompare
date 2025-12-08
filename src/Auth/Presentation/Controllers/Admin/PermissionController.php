<?php

namespace Src\Auth\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Src\Auth\Application\Actions\CreatePermissionAction;
use Src\Auth\Application\Actions\DeletePermissionAction;
use Src\Auth\Application\Actions\ListPermissionsAction;
use Src\Auth\Application\Actions\UpdatePermissionAction;
use Src\Auth\Application\DTOs\PermissionDTO;

/**
 * PermissionController controller.
 */
class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListPermissionsAction $list)
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
        
        return response()->json(['message' => 'Provide permission payload to store.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\Src\Auth\Presentation\Requests\PermissionRequest $request, CreatePermissionAction $create)
    {
        $perm = $create->execute(PermissionDTO::fromArray($request->validated()));
        
        return response()->json($perm, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Permission $permission)
    {
        
        return response()->json($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\Src\Auth\Presentation\Requests\PermissionRequest $request, Permission $permission, UpdatePermissionAction $update)
    {
        $permission = $update->execute($permission, PermissionDTO::fromArray($request->validated()));
        
        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Permission $permission, DeletePermissionAction $delete)
    {
        $delete->execute($permission);
        
        return response()->json(null, 204);

        return back()->with('status', 'Permission deleted');
    }
}
