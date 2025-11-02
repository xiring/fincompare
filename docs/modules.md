Modules & Admin Features

Auth (`Src/Auth`)

- Login/logout (`routes/auth.php`) via `LoginController`
- Profile management (edit profile, update profile, update password)
- Admin user management: Users, Roles, Permissions (Spatie)

Partners (`Src/Partners`)

- Partner CRUD for organizations providing products

Catalog (`Src/Catalog`)

- Product Categories CRUD
- Attributes per category (filterable/required flags, typed values)
- Products CRUD and CSV import flow
- Product Attribute Values storage for typed attributes

Content (`Src/Content`)

- Blog posts CRUD (with SEO fields and tags)
- CMS pages CRUD
- WYSIWYG image upload endpoint

Leads (`Src/Leads`)

- Lead intake and management (status, meta)
- Leads export (CSV)

Shared (`Src/Shared`)

- Activity log viewer (admin-only)
- Cross-cutting helpers/services used across domains

Admin Access

- All admin routes are under `/admin` with `auth` middleware; some endpoints are restricted to `role:admin`.


