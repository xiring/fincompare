<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Authenticate class.
 *
 * @package Src\Shared\Infrastructure\Http\Middleware
 */
class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
