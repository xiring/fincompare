<?php

namespace Src\Auth\Application\Actions;

use Src\Auth\Domain\Entities\User;

/**
 * UpdateUserProfileAction application action.
 */
class UpdateUserProfileAction
{
    public function execute(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $user->save();

        return $user;
    }
}
