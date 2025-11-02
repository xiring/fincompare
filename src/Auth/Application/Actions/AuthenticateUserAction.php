<?php
namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;

class AuthenticateUserAction
{
    public function __construct(private StatefulGuard $guard) {}

    public function execute(string $email, string $password, bool $remember = false): bool
    {
        return $this->guard->attempt(['email' => $email, 'password' => $password], $remember);
    }
}


