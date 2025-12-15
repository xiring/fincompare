<?php

namespace Src\Auth\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Src\Auth\Application\Actions\CreateAdminUserAction;
use Src\Auth\Application\Actions\DeleteAdminUserAction;
use Src\Auth\Application\Actions\ListAdminUsersAction;
use Src\Auth\Application\Actions\UpdateAdminUserAction;
use Src\Auth\Application\DTOs\AdminUserDTO;
use Src\Auth\Domain\Entities\User;

/**
 * AdminUserController controller.
 */
class AdminUserController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListAdminUsersAction $list)
    {
        $criteria = \Src\Shared\Application\Criteria\ListCriteria::fromArray([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
            'per_page' => $request->get('per_page', 20),
            'filters' => [
                'role_id' => $request->integer('role_id') ?: null,
            ],
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
        return response()->json(['message' => 'Provide user payload to store.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\Src\Auth\Presentation\Requests\AdminUserStoreRequest $request, CreateAdminUserAction $create)
    {
        $user = $create->execute(AdminUserDTO::fromArray($request->validated()));

        return response()->json($user->load('roles'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);
        return response()->json($user->load('roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        return response()->json($user->load('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\Src\Auth\Presentation\Requests\AdminUserUpdateRequest $request, int $id, UpdateAdminUserAction $update)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $user = $update->execute($user, AdminUserDTO::fromArray($request->validated()));

        return response()->json($user->load('roles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, DeleteAdminUserAction $delete)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $delete->execute($user);

        return response()->json(null, 204);
    }
}
