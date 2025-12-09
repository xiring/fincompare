Contributing & Coding Standards

Workflow

- Create feature branches from `main` or the designated dev branch
- Use small, focused pull requests with clear descriptions
- Link issues and include screenshots for UI changes

Commit messages

- Conventional style recommended: feat:, fix:, docs:, refactor:, test:, chore:
- Imperative mood, short subject, optional body for context

Code style

- PHP: PSR-12; run Pint: vendor/bin/pint
- Prefer explicit types and early returns
- Keep functions small and descriptive; avoid deep nesting
- Add comments only for non-obvious rationale or caveats

Frontend form submission

- **Always pass plain JavaScript objects to API modules**, not `FormData`
- API modules use `toFormData` utility to handle file uploads automatically
- Include file fields in the data object only when a new file is selected
- Example pattern:
  ```typescript
  const data = { name: form.name, ... };
  if (form.image) data.image = form.image;
  await store.createItem(data); // API module converts to FormData
  ```
- See `docs/typescript-migration.md` for detailed examples

Testing

- Add/adjust tests for new features and bug fixes
- Keep unit tests fast; push slower integration tests to separate suites

Security

- Avoid logging sensitive data
- Validate and authorize requests using policies/permissions

Reviews

- Address review feedback promptly; follow-up with incremental commits


