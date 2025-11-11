<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

/**
 * ValidateSignature class.
 */
class ValidateSignature extends Middleware
{
    protected $except = [];
}
