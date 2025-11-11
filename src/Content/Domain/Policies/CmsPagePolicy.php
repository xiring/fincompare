<?php

namespace Src\Content\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Content\Domain\Entities\CmsPage;

/**
 * CmsPagePolicy policy.
 */
class CmsPagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CmsPage $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CmsPage $model): bool
    {
        return true;
    }

    public function delete(User $user, CmsPage $model): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}
