<?php
namespace Src\Auth\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Spatie\Permission\Models\Permission;

/**
 * PermissionPolicy policy.
 *
 * @package Src\Auth\Domain\Policies
 */
class PermissionPolicy
{
    public function viewAny(User $user): bool { return $user->can('manage permissions'); }
    public function view(User $user, Permission $permission): bool { return $user->can('manage permissions'); }
    public function create(User $user): bool { return $user->can('manage permissions'); }
    public function update(User $user, Permission $permission): bool { return $user->can('manage permissions'); }
    public function delete(User $user, Permission $permission): bool { return method_exists($user,'hasRole') && $user->hasRole('admin'); }
}


