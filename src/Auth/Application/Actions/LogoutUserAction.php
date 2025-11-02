<?php
namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;

class LogoutUserAction
{
    public function __construct(private StatefulGuard $guard) {}

    public function execute(): void
    {
        $this->guard->logout();
    }
}


