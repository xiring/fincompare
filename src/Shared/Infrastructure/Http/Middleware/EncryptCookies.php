<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * EncryptCookies class.
 *
 * @package Src\Shared\Infrastructure\Http\Middleware
 */
class EncryptCookies extends Middleware
{
    protected $except = [];
}
