API & Routes

API (routes/api.php)

- GET /api/user — returns authenticated user (middleware: auth:sanctum)

Web (routes/web.php)

- GET / — welcome
- GET /dashboard — view, middleware: auth, verified
- Profile (middleware: auth)
  - GET /profile — edit profile
  - PATCH /profile — update profile
  - PUT /password — update password

Auth (routes/auth.php)

- GET /login — show login (guest)
- POST /login — login (guest)
- POST /logout — logout (auth)

Admin (routes/admin.php) — prefix: /admin, name: admin.*, middleware: web, auth, throttle:120,1

- Partners — resource controller
  - /admin/partners [index, create, store, show, edit, update, destroy]

- Product Categories — resource controller
  - /admin/product-categories [...]

- Attributes — resource controller (except show)
  - /admin/attributes [...]
  - GET /admin/attributes/by-category/{product_category}

- Products — resource controller + import
  - GET  /admin/products/import
  - POST /admin/products/import
  - /admin/products [...]

- Uploads
  - POST /admin/uploads/wysiwyg — upload WYSIWYG image

- Content
  - /admin/blogs — resource controller
  - /admin/cms-pages — resource controller

- Leads
  - /admin/leads — [index, show, update]
  - GET /admin/leads-export — CSV export

- Admin-only (role:admin)
  - GET /admin/activity — activity log
  - /admin/users — users resource
  - /admin/roles — roles resource
  - /admin/permissions — permissions resource (except show)

Notes

- Resource routes map to conventional controller actions; see controllers under each domain `Presentation/Controllers` folder.
- Some routes require additional authorization (policies/permissions) beyond authentication.


