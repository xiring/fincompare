<?php

namespace Src\Shared\Infrastructure\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * TrustHosts class.
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
