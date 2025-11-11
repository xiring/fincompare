<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * VerifyCsrfToken class.
 */
class VerifyCsrfToken extends Middleware
{
    protected $except = [];
}
