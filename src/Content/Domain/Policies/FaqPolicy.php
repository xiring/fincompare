<?php

namespace Src\Content\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Content\Domain\Entities\Faq;

/**
 * FaqPolicy policy.
 */
class FaqPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Faq $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Faq $model): bool
    {
        return true;
    }

    public function delete(User $user, Faq $model): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}
