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
        $criteria = \Src\Shared\Application\Criteria\ListCriteria::fromArray([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
            'per_page' => $request->get('per_page', 20),
        ]);

        $items = $list->execute($criteria);

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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);
        $this->authorize('view', $permission);
        return response()->json($permission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);
        $this->authorize('update', $permission);
        return response()->json($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\Src\Auth\Presentation\Requests\PermissionRequest $request, int $id, UpdatePermissionAction $update)
    {
        $permission = Permission::findOrFail($id);
        $this->authorize('update', $permission);
        $permission = $update->execute($permission, PermissionDTO::fromArray($request->validated()));

        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, DeletePermissionAction $delete)
    {
        $permission = Permission::findOrFail($id);
        $this->authorize('delete', $permission);
        $delete->execute($permission);

        return response()->json(null, 204);
    }
}
