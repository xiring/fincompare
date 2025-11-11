<?php
namespace Src\Auth\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Spatie\Permission\Models\Role;

/**
 * RolePolicy policy.
 *
 * @package Src\Auth\Domain\Policies
 */
class RolePolicy
{
    public function viewAny(User $user): bool { return $user->can('manage roles'); }
    public function view(User $user, Role $role): bool { return $user->can('manage roles'); }
    public function create(User $user): bool { return $user->can('manage roles'); }
    public function update(User $user, Role $role): bool { return $user->can('manage roles'); }
    public function delete(User $user, Role $role): bool { return method_exists($user,'hasRole') && $user->hasRole('admin'); }
}


