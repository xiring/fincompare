# Test Suite Conventions

This project uses Pest for testing. Tests are grouped by feature area to keep things easy to navigate and scale.

## Folder structure

- Feature/
  - Admin/
    - AdminAccessTest.php
    - AdminRoutesTest.php
    - Auth/
      - AdminUserCrudTest.php
      - AdminRoleCrudTest.php
      - AdminPermissionCrudTest.php
    - Catalog/
      - AdminProductCrudTest.php
      - AdminProductCategoryCrudTest.php
      - AdminAttributeCrudTest.php
      - AdminProductImportTest.php
      - ProductIndexTest.php
    - Content/
      - AdminBlogCrudTest.php
      - AdminCmsPageCrudTest.php
      - AdminFaqCrudTest.php
    - Settings/
      - SiteSettingsUpdateTest.php
  - Auth/
    - AuthFlowTest.php
    - ProfileControllerTest.php
  - Public/
    - PublicPagesTest.php
    - CatalogPublicTest.php
    - ContactFormTest.php
    - LeadCaptureTest.php

Use the closest subfolder that matches the domain or controller you are testing. Prefer fine‑grained folders (e.g., Admin/Catalog) over dumping in a single place.

## Naming

- File names end with `Test.php` and describe the subject, e.g. `AdminProductCrudTest.php`.
- Test descriptions should communicate intent, e.g. `it('stores product via json', ...)`.

## Shared helpers

Defined in `tests/Pest.php`:

- `actingAsAdmin(): User` — create and authenticate an admin user.
- `actingAsRole(string $role): User` — create and authenticate a user with the provided role.
- `actAs(User $user, string $role): User` — assign role to an existing user and authenticate.

Always use these helpers for admin/authenticated flows.

## Data & fakes

- Use model factories and `fake()` for realistic data.
- Use `Storage::fake('public' | 'local')` for file operations and assert with `Storage::disk(...)->exists(...)`.
- Use `Mail::fake()` for mail assertions.
- Use `withoutMiddleware(ConvertEmptyStringsToNull::class)` if you need to keep empty string inputs (e.g., honeypot fields).

## HTTP verbs and JSON

- Prefer JSON endpoints for admin CRUD tests (`postJson`, `putJson`, `deleteJson`, etc.).
- Match controller verbs:
  - Update routes may be `PATCH` or `PUT` depending on the controller (see the route/controller signature and follow it).

## Datasets

- Use `dataset()` for parameterized tests (e.g., toggling remember flag, multiple image types). Keeps tests concise and robust.

## Choosing a folder for new tests

- Admin routes/controllers: `Feature/Admin/...` under the appropriate domain (Catalog/Content/Auth/Settings).
- Public pages and flows: `Feature/Public/...`.
- Authentication and profile: `Feature/Auth/...`.

If unsure, mirror the controller namespace under `Feature/` (e.g., `Src\Catalog\Presentation\Controllers\Admin\XController` → `Feature/Admin/Catalog/*`).

## Example test skeleton

```php
<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Catalog\Domain\Entities\Product;

uses(RefreshDatabase::class);

it('updates product via json', function () {
    actingAsAdmin();
    $product = Product::factory()->create();

    $this->putJson(route('admin.products.update', $product), [
        'partner_id' => $product->partner_id,
        'product_category_id' => $product->product_category_id,
        'name' => 'Updated Name',
        'status' => 'active',
    ])->assertOk()->assertJson(['name' => 'Updated Name']);
});
```

## Running tests

```bash
./vendor/bin/pest
```

Use filters while developing:

```bash
./vendor/bin/pest --group=feature --filter=AdminProduct
```


