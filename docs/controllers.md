Controllers Reference

This reference summarizes key controllers and actions inferred from `routes/*`.

Auth

- LoginController
  - GET /login → create
  - POST /login → store
  - POST /logout → destroy

- ProfileController (middleware: auth)
  - GET /profile → edit
  - PATCH /profile → update
  - PUT /password → updatePassword

Admin (prefix: /admin, name: admin.)

- Partners — PartnerController
  - Resource: index, create, store, show, edit, update, destroy

- Product Categories — ProductCategoryController
  - Resource: index, create, store, show, edit, update, destroy

- Attributes — AttributeController
  - Resource: index, create, store, edit, update, destroy (no show)
  - GET attributes/by-category/{product_category} → byCategory

- Products — ProductController
  - Resource: index, create, store, show, edit, update, destroy
  - Import — ProductImportController
    - GET products/import → create (upload form)
    - POST products/import → store (queue job)

- Forms — FormController
  - Resource: index, create, store, show, edit, update, destroy
  - POST forms/{id}/duplicate → duplicate

- Uploads — UploadController
  - POST uploads/wysiwyg → storeWysiwygImage

- Content
  - BlogPostController — resource
  - CmsPageController — resource

- Leads — LeadController
  - Resource: index, show, update
  - GET leads-export → exportCsv

- Activity — ActivityLogController (role:admin)
  - GET activity → index

- RBAC (role:admin)
  - AdminUserController — users resource
  - RoleController — roles resource
  - PermissionController — permissions resource (except show)

Notes

- Resource controllers follow Laravel conventions. Check policy/permission middleware for additional access rules.


