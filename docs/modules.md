Modules & Admin Features

Auth (`Src/Auth`)

- Login/logout (`routes/auth.php`) via `LoginController`
- Profile management (edit profile, update profile, update password)
- Admin user management: Users, Roles, Permissions (Spatie)

Partners (`Src/Partners`)

- Partner CRUD for organizations providing products

Catalog (`Src/Catalog`)

- Product Categories CRUD
  - Image upload support
  - Pre-form and post-form associations (links to Forms module)
- Attributes per category (filterable/required flags, typed values)
- Products CRUD and CSV import flow
  - Image upload support
- Product Attribute Values storage for typed attributes

Content (`Src/Content`)

- Blog posts CRUD (with SEO fields and tags)
- CMS pages CRUD
- WYSIWYG image upload endpoint

Forms (`Src/Forms`)

- Dynamic forms CRUD (pre-form and post-form types)
- Form inputs management (text, textarea, dropdown, checkbox)
- Forms can be associated with product categories as pre-form or post-form
- Form validation rules and conditional logic

Leads (`Src/Leads`)

- Lead intake and management (status, meta)
- Leads export (CSV)

Shared (`Src/Shared`)

- Activity log viewer (admin-only)
- Cross-cutting helpers/services used across domains

Admin Access

- All admin routes are under `/admin` with `auth` middleware; some endpoints are restricted to `role:admin`.


