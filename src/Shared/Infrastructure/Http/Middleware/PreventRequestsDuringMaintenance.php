<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * PreventRequestsDuringMaintenance class.
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    protected $except = [];
}
