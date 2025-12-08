<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Src\Shared\Domain\Repositories\ActivityLogRepositoryInterface;
use Src\Shared\Infrastructure\Persistence\EloquentActivityLogRepository;
use Illuminate\Support\Str;

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

        // Bind ActivityLog repository
        $this->app->bind(ActivityLogRepositoryInterface::class, EloquentActivityLogRepository::class);
    }

    public function boot(): void
    {
        // Anonymous components are placed under resources/views/components,
        // so <x-app-layout> and <x-guest-layout> resolve without manual registration.

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
