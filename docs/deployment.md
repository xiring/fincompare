Deployment & Operations

Build artifacts

- Composer prod install: composer install --no-dev --optimize-autoloader --no-interaction
- Frontend build: pnpm install --frozen-lockfile && pnpm run build
  - Note: Production builds skip type checking for speed. Run `pnpm run type-check` separately in CI/CD if needed.

App bootstrap

- php artisan key:generate (first deploy)
- php artisan storage:link
- php artisan migrate --force
- php artisan db:seed --force (first deploy, if needed)

Optimize

- php artisan config:cache
- php artisan route:cache
- php artisan view:cache

Queues & schedule

- Run a queue worker or Horizon
  - php artisan queue:work --daemon --sleep=3 --tries=3
  - or Horizon (configure supervisors)
- Add cron entry: * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1

Environment

- Ensure correct permissions for `storage/` and `bootstrap/cache/`
- Set APP_ENV=production, APP_DEBUG=false
- Monitor logs: storage/logs/laravel.log or centralized logging

Rollbacks

- php artisan migrate:rollback --step=1 --force


