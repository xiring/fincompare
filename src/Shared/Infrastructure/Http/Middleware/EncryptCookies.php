<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * EncryptCookies class.
 */
class EncryptCookies extends Middleware
{
    protected $except = [];
}
