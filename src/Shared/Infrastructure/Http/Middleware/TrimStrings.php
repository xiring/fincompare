<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * TrimStrings class.
 *
 * @package Src\Shared\Infrastructure\Http\Middleware
 */
class TrimStrings extends Middleware
{
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
