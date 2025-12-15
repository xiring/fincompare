<?php

namespace Src\Catalog\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Catalog\Domain\Entities\Group;

/**
 * GroupPolicy policy.
 */
class GroupPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Group $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Group $model): bool
    {
        return true;
    }

    public function delete(User $user, Group $model): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}


