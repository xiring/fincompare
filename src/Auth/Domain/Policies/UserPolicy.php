<?php

namespace Src\Auth\Domain\Policies;

use Src\Auth\Domain\Entities\User;

/**
 * UserPolicy policy.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage users');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('manage users');
    }

    public function create(User $user): bool
    {
        return $user->can('manage users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('manage users');
    }

    public function delete(User $user, User $model): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}
