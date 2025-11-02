Testing & QA

Test runner

- Pest is configured (see `tests/`)
- Run all tests: php artisan test
- Or: ./vendor/bin/pest

Environment

- Use a separate testing database; consider sqlite memory for speed
- Run migrations before tests if using a real DB

Factories & Seeders

- Model factories live under `database/factories`
- Use seeders for integration test bootstrapping when needed

Coverage

- Example: Xdebug + `./vendor/bin/pest --coverage`

CI suggestions

- composer install --no-interaction --prefer-dist --no-progress
- php artisan key:generate
- php artisan migrate --env=testing --force
- php artisan test --testsuite=Unit,Feature


