<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Src\Auth\Domain\Entities\User;

/**
 * UpdateUserPasswordAction application action.
 */
class UpdateUserPasswordAction
{
    public function execute(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages(['current_password' => __('The provided password does not match your current password.')]);
        }

        $user->update(['password' => $newPassword]);
    }
}
