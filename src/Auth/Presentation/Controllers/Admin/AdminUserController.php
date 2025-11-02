<?php
namespace Src\Auth\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Src\Auth\Domain\Entities\User;
use Spatie\Permission\Models\Role;
use Src\Auth\Application\Actions\ListAdminUsersAction;
use Src\Auth\Application\Actions\CreateAdminUserAction;
use Src\Auth\Application\Actions\UpdateAdminUserAction;
use Src\Auth\Application\Actions\DeleteAdminUserAction;
use Src\Auth\Application\DTOs\AdminUserDTO;

class AdminUserController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request, ListAdminUsersAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir')
        ], (int)$request->get('per_page',20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.users.index', compact('items'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message'=>'Provide user payload to store.']);
        $roles = Role::orderBy('name')->get(['id','name']);
        return view('admin.users.create', compact('roles'));
    }

    public function store(\Src\Auth\Presentation\Requests\AdminUserStoreRequest $request, CreateAdminUserAction $create)
    {
        $user = $create->execute(AdminUserDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($user->load('roles'),201);
        return redirect()->route('admin.users.index')->with('status','User created');
    }

    public function edit(Request $request, User $user)
    {
        if ($request->wantsJson()) return response()->json($user->load('roles'));
        $roles = Role::orderBy('name')->get(['id','name']);
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(\Src\Auth\Presentation\Requests\AdminUserUpdateRequest $request, User $user, UpdateAdminUserAction $update)
    {
        $user = $update->execute($user, AdminUserDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($user->load('roles'));
        return redirect()->route('admin.users.index')->with('status','User updated');
    }

    public function destroy(Request $request, User $user, DeleteAdminUserAction $delete)
    {
        $delete->execute($user);
        if ($request->wantsJson()) return response()->json(null,204);
        return back()->with('status','User deleted');
    }
}


