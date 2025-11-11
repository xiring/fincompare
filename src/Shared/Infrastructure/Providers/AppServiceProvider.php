<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Src\Shared\Presentation\View\Components\AppLayout;
use Src\Shared\Presentation\View\Components\GuestLayout;

/**
 * AppServiceProvider service provider.
 */
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

        // Detect N+1s and missing attributes (fail fast in non-prod, log in prod)
        Model::preventLazyLoading(! app()->isProduction());
        Model::handleLazyLoadingViolationUsing(function ($model, string $relation) {
            $message = 'N+1 detected on '.get_class($model).'::'.$relation;
            if (app()->isProduction()) {
                Log::warning($message);
            } else {
                throw new \RuntimeException($message);
            }
        });

        // Log slow queries (>500ms) to help spot hotspots
        DB::listen(function ($query) {
            $timeMs = property_exists($query, 'time') ? $query->time : null;
            if ($timeMs !== null && $timeMs > 500) {
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms' => $timeMs,
                ]);
            }
        });

        // Add a helper to retry transactions on deadlocks with exponential backoff
        if (! DB::hasMacro('transactionWithRetry')) {
            DB::macro('transactionWithRetry', function (callable $callback, int $attempts = 5, int $initialSleepMs = 100) {
                $sleepMs = $initialSleepMs;
                for ($i = 1; $i <= $attempts; $i++) {
                    try {
                        return DB::transaction($callback, 5);
                    } catch (QueryException $e) {
                        $message = $e->getMessage();
                        $isDeadlock = Str::contains(Str::lower($message), [
                            'deadlock',            // MySQL, Postgres
                            'database is locked', // SQLite
                            'could not obtain lock',
                        ]);
                        if (! $isDeadlock || $i === $attempts) {
                            throw $e;
                        }
                        usleep($sleepMs * 1000);
                        $sleepMs = min($sleepMs * 2, 2000); // cap backoff at 2s

                        continue;
                    }
                }
            });
        }

        // Force HTTPS URL generation in production
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
