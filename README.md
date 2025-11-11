## FinCompare

A modular Laravel application for managing partners, product categories, products, and leads. Includes queued product CSV import (Redis), background processing with Horizon, telescoped debugging, and a Pest test suite.

### Tech stack
- PHP >= 8.2, Laravel 12
- MySQL/PostgreSQL (app-agnostic), SQLite for tests
- Redis for queues
- Horizon (admin-gated, enabled in production)
- Telescope (admin-gated, enabled in production)
- PestPHP for tests, Tailwind/Vite for assets

### Quick start
1) Install dependencies
```bash
composer install
npm install && npm run build # or: npm run dev
```

2) Configure environment
```bash
cp .env.example .env
# set DB_*, REDIS_*, APP_URL, etc.
echo "QUEUE_CONNECTION=redis" >> .env
php artisan key:generate
```

3) Migrate (and optionally seed)
```bash
php artisan migrate
# php artisan db:seed
```

4) Run the app
```bash
php artisan serve
```

### Queues, Redis, and Horizon
- Ensure Redis is running.
- Horizon is configured via `config/horizon.php` and gated to users with the `admin` role.
- Access Horizon at `/horizon` (requires auth + admin role). It is allowed in production behind the gate.
- Start workers locally:
```bash
php artisan horizon
# or
php artisan queue:work --queue=imports,default
```

### Product import (CSV)
- UI: Admin → Products → Import (`/admin/products/import`).
- The form queues a job to parse and create products using your existing domain actions.
- Queued on `imports` (Redis). Monitor via Horizon.
- CSV format (required headers):
  - `name, partner_id, product_category_id`
  - Optional: `slug, description, is_featured, status, attributes`
  - `attributes` is JSON mapping of `attribute_id` → value (text/number/boolean/json supported per attribute type).
- Sample file: `resources/examples/product_import_sample.csv`

### Admin gating for Horizon and Telescope
- Horizon and Telescope are enabled in production but protected by an auth gate: only users with the `admin` role may access.
- Paths:
  - Horizon: `/horizon`
  - Telescope: `/telescope`

### Testing
- Test runner: Pest.
- In-memory SQLite is configured for tests in `phpunit.xml`.
- Base test config in `tests/TestCase.php` sets factory guessing for namespaced models and applies standard Laravel testing traits.
- Run:
```bash
php artisan test
# or
./vendor/bin/pest
```
- Optional: create a `.env.testing` (gitignored) with:
```
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
MAIL_MAILER=array
TELESCOPE_ENABLED=false
```

### Common commands
```bash
# Clear caches
php artisan route:clear && php artisan config:clear && php artisan cache:clear

# Generate IDE helper models (optional if you use barryvdh/laravel-ide-helper)
# php artisan ide-helper:models -M
```

### Genereate Docblok
```bash
 php scripts/generate_docblocks.php

```
