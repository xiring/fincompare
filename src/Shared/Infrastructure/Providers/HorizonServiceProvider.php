<?php
namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class HorizonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!class_exists(Horizon::class)) return;

        Horizon::auth(function ($request) {
            $user = $request->user();
            return $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
        });
    }
}


