<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * VerifyCsrfToken class.
 *
 * @package Src\Shared\Infrastructure\Http\Middleware
 */
class VerifyCsrfToken extends Middleware
{
    protected $except = [];
}
