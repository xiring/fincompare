<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    protected $except = [];
}
