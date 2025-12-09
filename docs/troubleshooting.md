Troubleshooting & FAQ

Common issues

- Blank pages or 500 after deploy
  - Run: php artisan config:clear && php artisan cache:clear && php artisan view:clear
  - Rebuild caches: php artisan config:cache && php artisan route:cache

- Storage write errors
  - Ensure `storage/` and `bootstrap/cache/` are writable by the web user
  - Run: php artisan storage:link

- 419 Page Expired (CSRF)
  - Check session driver and domain config; ensure APP_URL is correct

- Queue jobs not processing
  - Verify queue worker or Horizon is running; check QUEUE_CONNECTION

- Route not found after adding controller
  - Run: php artisan route:clear (then route:cache in prod)

- DB errors after pulling changes
  - Run: php artisan migrate --force

- WYSIWYG uploads failing
  - Confirm `FILESYSTEM_DISK=public` and storage symlink exists

- Form submission not sending data (empty payload)
  - **Issue**: Form data not appearing in network request
  - **Cause**: Manually creating `FormData` and passing it to API module that expects plain object
  - **Solution**: Pass plain JavaScript object to store methods; API modules handle `FormData` conversion automatically
  - See `docs/typescript-migration.md` section "Form Submission with File Uploads" for correct pattern

FAQ

- How do I access the admin panel?
  - Visit /admin (requires authentication and appropriate role)

- How do I create an initial admin user?
  - Run seeders (see DatabaseSeeder). Assign role via Users section or seeder.


