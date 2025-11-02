<?php
namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Shared\Presentation\View\Components\AppLayout;
use Src\Shared\Presentation\View\Components\GuestLayout;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the stateful guard so actions can type-hint it
        $this->app->bind(StatefulGuard::class, function ($app) {
            /** @var AuthFactory $auth */
            $auth = $app->make(AuthFactory::class);
            $guardName = config('auth.defaults.guard', 'web');
            return $auth->guard($guardName);
        });
    }
    public function boot(): void
    {
        // Register component namespace so <x-app-layout> and <x-guest-layout> still resolve
        Blade::componentNamespace('Src\\Shared\\Presentation\\View\\Components', '');

        // Explicit aliases for unprefixed component tags
        Blade::component('app-layout', AppLayout::class);
        Blade::component('guest-layout', GuestLayout::class);

        // Make model factories work with Src\* namespaced models
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}


