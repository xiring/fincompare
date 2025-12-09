Architecture Overview

Stack

- PHP 8.2, Laravel 12
- Frontend: Vue 3 (Composition API), TypeScript, Vite, TailwindCSS, Pinia
- Auth: Laravel Sanctum
- Observability: Telescope (dev), Debugbar (dev)
- Jobs/Queues: Laravel Queue (Horizon-ready)
- RBAC: spatie/laravel-permission
- Audit: spatie/laravel-activitylog
- Testing: Pest

Project layout

- Application code lives under `src/` using domain-based folders:
  - `Src/Auth`
  - `Src/Catalog`
  - `Src/Content`
  - `Src/Leads`
  - `Src/Partners`
  - `Src/Shared`
  - `Src/Providers`

Routing

- Public web routes: `routes/web.php`
- Auth routes (login/logout): `routes/auth.php`
- Admin routes (CRUD, exports, RBAC): `routes/admin.php` with prefix `admin` and `auth` middleware
- API routes: `routes/api.php` (Sanctum protected `/api/user`)

Admin surface (high level)

- Partners: CRUD (with logo upload)
- Product Categories: CRUD (with image upload, pre-form/post-form associations)
- Attributes: CRUD (+ by-category endpoint)
- Products: import and CRUD (with image upload)
- Forms: CRUD for dynamic forms (pre-form/post-form types)
- Content: Blogs, CMS Pages CRUD (with featured image upload); WYSIWYG image uploads
- Leads: index/show/update + CSV export
- RBAC: Users, Roles, Permissions (admin-only)
- Activity log (admin-only)

Data model (summary)

- `partners (1) — (n) products`
- `product_categories (1) — (n) attributes`
- `product_categories (1) — (n) products`
- `product_categories (n) — (1) forms` (pre_form_id, post_form_id)
- `forms (1) — (n) form_inputs`
- `products (1) — (n) product_attribute_values` linked to `(n) attributes`
- `leads` optionally linked to `product_categories` and `products`
- Content: `blog_posts`, `cms_pages` with SEO fields and slugs
- RBAC tables from Spatie (roles, permissions, pivots)

Conventions

- Soft deletes enabled on core tables (see `2025_11_02_200000_add_soft_deletes_to_tables.php`).
- Slugs used for friendly URLs on content and catalog entities.
- Controllers live under `Presentation/Controllers` inside each domain folder.


