<?php

namespace Src\Forms\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Forms\Domain\Entities\Form;

/**
 * FormPolicy policy.
 */
class FormPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Form $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Form $model): bool
    {
        return true;
    }

    public function delete(User $user, Form $model): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}

