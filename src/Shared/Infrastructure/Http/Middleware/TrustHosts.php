<?php
namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * TrustHosts class.
 *
 * @package Src\Shared\Infrastructure\Http\Middleware
 */
class TrustHosts extends Middleware
{
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
