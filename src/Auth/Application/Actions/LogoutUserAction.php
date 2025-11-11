<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Auth\StatefulGuard;

/**
 * LogoutUserAction application action.
 */
class LogoutUserAction
{
    public function __construct(private StatefulGuard $guard) {}

    public function execute(): void
    {
        $this->guard->logout();
    }
}
