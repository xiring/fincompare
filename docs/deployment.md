Deployment & Operations

Build artifacts

- Composer prod install: composer install --no-dev --optimize-autoloader --no-interaction
- Frontend build: pnpm install --frozen-lockfile && pnpm run build
  - Note: Production builds skip type checking for speed. Run `pnpm run type-check` separately in CI/CD if needed.
  - For faster builds, ensure pnpm store is cached between builds (pnpm uses a global store)
  - Build is optimized with esbuild minification and chunk splitting

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

Horizon Setup

- Ensure `APP_ENV` matches Horizon environment config (production, local, or staging)
- Verify Redis is running and accessible
- Supervisor config example (`/etc/supervisor/conf.d/fincompare-app.conf`):
  ```
  [program:fincompare_horizon]
  process_name=%(program_name)s_%(process_num)02d
  command=php /var/www/html/fincompare/artisan horizon
  autostart=true
  autorestart=true
  redirect_stderr=true
  user=www-data
  stdout_logfile=/var/www/html/fincompare/storage/logs/horizon.log
  stdout_logfile_maxbytes=10MB
  logfile_backups=14
  stopwaitsecs=3600
  ```
- After supervisor config changes: `sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl restart horizon`
- If Horizon shows "Inactive": Check Redis connection, verify APP_ENV, restart Horizon, clear config cache

Environment

- Ensure correct permissions for `storage/` and `bootstrap/cache/`
- Set APP_ENV=production, APP_DEBUG=false
- Monitor logs: storage/logs/laravel.log or centralized logging

Rollbacks

- php artisan migrate:rollback --step=1 --force


