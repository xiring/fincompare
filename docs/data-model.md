Database Schema & Data Model

Catalog & Partners

- partners
  - id, name, slug, logo_path?, website_url?, contact_email?, contact_phone?, status, timestamps, deleted_at
  - Relationships: hasMany products

- product_categories
  - id, name, slug, description?, image?, is_active, pre_form_id? (FK), post_form_id? (FK), timestamps, deleted_at
  - Relationships: hasMany attributes, hasMany products, belongsTo preForm (Form), belongsTo postForm (Form)

- attributes
  - id, product_category_id (FK), name, slug, data_type, unit?, is_filterable, is_required, sort_order, timestamps, deleted_at
  - Unique: [product_category_id, slug]

- products
  - id, partner_id (FK), product_category_id (FK), name, slug, description?, image?, is_featured, status, timestamps, deleted_at
  - Unique: slug

- product_attribute_values
  - id, product_id (FK), attribute_id (FK), value_text?, value_number?, value_boolean?, value_json?, timestamps, deleted_at
  - Unique: [product_id, attribute_id]; Index: attribute_id

Leads

- leads
  - id, product_category_id (FK nullable), product_id (FK nullable), full_name, email?, mobile_number?, message?, status, source?, meta (json)?, timestamps, deleted_at
  - Index: status

Content

- blog_posts
  - id, title, slug, category?, content?, featured_image?, status, seo_title?, seo_description?, seo_keywords?, tags (json)?, timestamps, deleted_at
  - Index: [status, category]; Unique: slug

- cms_pages
  - id, title, slug, seo_title?, seo_description?, seo_keywords?, content?, status, timestamps, deleted_at
  - Index: status; Unique: slug

Users & RBAC

- users
  - id, name, email (unique), email_verified_at?, password, remember_token, timestamps, deleted_at

- Forms
  - forms
    - id, name, slug, description?, status, type (pre_form|post_form), timestamps, deleted_at
    - Unique: slug
    - Relationships: hasMany formInputs, hasMany productCategoriesAsPreForm, hasMany productCategoriesAsPostForm
  - form_inputs
    - id, form_id (FK), label, name, type (text|textarea|dropdown|checkbox), options (json)?, placeholder?, help_text?, is_required, validation_rules?, sort_order, timestamps, deleted_at
    - Relationships: belongsTo form

- Spatie Permission tables
  - permissions, roles, role_has_permissions, model_has_roles, model_has_permissions
  - See `2025_11_02_180324_create_permission_tables.php` and `config/permission.php`

Notes

- Soft deletes are enabled for core tables (see `2025_11_02_200000_add_soft_deletes_to_tables.php`).
- Slugs provide stable identifiers for public/content entities.
- Numeric vs text attribute values are stored in typed columns to support filtering and sorting.


