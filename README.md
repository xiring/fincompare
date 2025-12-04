## FinCompare

A modular Laravel application for managing partners, product categories, products, and leads. Includes queued product CSV import (Redis), background processing with Horizon, telescoped debugging, and a Pest test suite.

### Tech stack
- PHP >= 8.2, Laravel 12
- MySQL/PostgreSQL (app-agnostic), SQLite for tests
- Redis for queues
- Horizon (admin-gated, enabled in production)
- Telescope (admin-gated, enabled in production)
- PestPHP for tests, Tailwind/Vite for assets

### Quick start
1) Install dependencies
```bash
composer install
npm install && npm run build # or: npm run dev
```

2) Configure environment
```bash
cp .env.example .env
# set DB_*, REDIS_*, APP_URL, etc.
echo "QUEUE_CONNECTION=redis" >> .env
php artisan key:generate
```

3) Migrate (and optionally seed)
```bash
php artisan migrate
# php artisan db:seed
```

4) Run the app
```bash
php artisan serve
```

### Queues, Redis, and Horizon
- Ensure Redis is running.
- Horizon is configured via `config/horizon.php` and gated to users with the `admin` role.
- Access Horizon at `/horizon` (requires auth + admin role). It is allowed in production behind the gate.
- Start workers locally:
```bash
php artisan horizon
# or
php artisan queue:work --queue=imports,default
```

### Product import (CSV)
- UI: Admin → Products → Import (`/admin/products/import`).
- The form queues a job to parse and create products using your existing domain actions.
- Queued on `imports` (Redis). Monitor via Horizon.
- CSV format (required headers):
  - `name, partner_id, product_category_id`
  - Optional: `slug, description, is_featured, status, attributes`
  - `attributes` is JSON mapping of `attribute_id` → value (text/number/boolean/json supported per attribute type).
- Sample file: `resources/examples/product_import_sample.csv`

### Admin gating for Horizon and Telescope
- Horizon and Telescope are enabled in production but protected by an auth gate: only users with the `admin` role may access.
- Paths:
  - Horizon: `/horizon`
  - Telescope: `/telescope`

### Testing
- Test runner: Pest.
- In-memory SQLite is configured for tests in `phpunit.xml`.
- Base test config in `tests/TestCase.php` sets factory guessing for namespaced models and applies standard Laravel testing traits.
- Run:
```bash
php artisan test
# or
./vendor/bin/pest
```
- Optional: create a `.env.testing` (gitignored) with:
```
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
MAIL_MAILER=array
TELESCOPE_ENABLED=false
```

### Common commands
```bash
# Clear caches
php artisan route:clear && php artisan config:clear && php artisan cache:clear

# Generate IDE helper models (optional if you use barryvdh/laravel-ide-helper)
# php artisan ide-helper:models -M
```

### Generate Docblocks
```bash
php scripts/generate_docblocks.php
```

---

## Vue SPA Architecture

### Overview

The public-facing pages are built as a Vue.js Single Page Application (SPA) following senior-level development practices with a clean, maintainable architecture. The SPA is located in `resources/js/public/`.

### Directory Structure

```
resources/js/public/
├── components/          # Reusable Vue components
├── composables/         # Vue composition API composables
├── layouts/            # Layout components
├── pages/              # Page components (routes)
├── services/           # API service layer
├── stores/             # State management (Pinia)
├── utils/              # Utility functions and constants
├── app.js              # Application entry point
├── App.vue             # Root component
└── router.js           # Vue Router configuration
```

### Architecture Principles

#### 1. Service Layer (`services/`)
- Centralized API client with interceptors
- Consistent error handling
- Request/response transformation
- Type-safe API methods

#### 2. Composables (`composables/`)
- Reusable business logic
- Separation of concerns
- Reactive state management
- Error handling built-in

#### 3. Utilities (`utils/`)
- Pure functions
- Constants management
- Validation helpers
- Formatting utilities

#### 4. Components
- Single responsibility
- Props validation
- Emit events properly
- Composition API preferred

### Key Composables

#### `useAsyncData`
Generic async data fetching with loading, error, and retry logic.

```javascript
const { data, loading, error, execute, refresh } = useAsyncData(fetcher, options);
```

#### `usePagination`
Pagination state management for infinite scroll and paginated lists.

```javascript
const { currentPage, hasMore, loadMore, reset } = usePagination(options);
```

#### `useLocalStorage`
Reactive localStorage with automatic sync.

```javascript
const { value, remove } = useLocalStorage('key', defaultValue);
```

#### `useCompare`
Product comparison functionality with localStorage sync.

```javascript
const { compareIds, toggleCompare, isInCompare, clearAll } = useCompare();
```

### API Service

All API calls go through the centralized service:

```javascript
import { apiService } from '@/services/api';

// Usage
const response = await apiService.getProducts({ page: 1 });
const product = await apiService.getProduct(slug);
```

### Best Practices

#### 1. Data Fetching
**Always use composables for data fetching** - Don't call API directly in components

```javascript
// ❌ Bad: Direct API call in component
const products = ref([]);
axios.get('/api/products').then(res => products.value = res.data);

// ✅ Good: Use composable
const { products, loading, error } = useProducts();
```

**Benefits:**
- Centralized error handling
- Reusable logic across components
- Consistent loading states
- Easier testing and maintenance

#### 2. Constants Management
**Use constants** - Don't hardcode strings/numbers

```javascript
// ❌ Bad: Hardcoded values
localStorage.setItem('compare', JSON.stringify(ids));
if (status === 'active') { ... }

// ✅ Good: Use constants
import { STORAGE_KEYS, PRODUCT_STATUS } from '@/utils/constants';
localStorage.setItem(STORAGE_KEYS.COMPARE, JSON.stringify(ids));
if (status === PRODUCT_STATUS.ACTIVE) { ... }
```

#### 3. Error Handling
**Handle errors gracefully** - Show user-friendly messages

```javascript
// ✅ Good: User-friendly error messages
try {
  await fetchData();
} catch (error) {
  toast.error('Unable to load products. Please try again later.');
  console.error('Product fetch error:', error);
}
```

#### 4. Loading States
**Always show loading indicators** - Provide visual feedback during async operations

```javascript
// ✅ Good: Loading skeleton + progress bar
<div v-if="loading" class="skeleton-loader">...</div>
<div v-else>...</div>
```

#### 5. Type Safety
**Use JSDoc comments** - Better IDE support and documentation

```javascript
/**
 * Fetches a product by slug
 * @param {string} slug - Product slug
 * @returns {Promise<{id: number, name: string, price: number}>}
 */
async function getProduct(slug) {
  // ...
}
```

#### 6. Performance Optimization
**Use computed properties, lazy loading, and pagination**

```javascript
// ✅ Good: Computed for derived state
const filteredProducts = computed(() => {
  return products.value.filter(p => p.status === 'active');
});

// ✅ Good: Lazy loading routes
const ProductShow = () => import('@pages/products/Show.vue');

// ✅ Good: Infinite scroll pagination
const { loadMore, hasMore } = usePagination();
```

**Additional Performance Tips:**
- Use `v-show` for frequent toggles, `v-if` for conditional rendering
- Debounce search inputs (500ms default)
- Use `Object.freeze()` for static data
- Implement virtual scrolling for large lists

#### 7. Accessibility
**Proper ARIA labels and keyboard navigation**

```vue
<!-- ✅ Good: Accessible form -->
<form @submit.prevent="handleSubmit">
  <label for="email">Email Address</label>
  <input
    id="email"
    type="email"
    aria-required="true"
    aria-describedby="email-error"
    :aria-invalid="hasError"
  />
  <div id="email-error" role="alert" v-if="hasError">
    {{ errorMessage }}
  </div>
</form>
```

#### 8. Component Design
- **Single Responsibility**: Each component should do one thing well
- **Props Validation**: Always define prop types and defaults
- **Event Naming**: Use kebab-case for events (`@update:value`)
- **Composition API**: Prefer `<script setup>` over Options API

#### 9. State Management
- **Local State**: Use `ref`/`reactive` for component-specific state
- **Shared State**: Use Pinia stores for global state
- **Server State**: Use composables that sync with API

#### 10. Code Organization
- **File Naming**: Use PascalCase for components (`ProductCard.vue`)
- **Folder Structure**: Group related files together
- **Barrel Exports**: Use `index.js` for clean imports
- **Separation of Concerns**: Keep business logic in composables, not components

### Design Patterns

#### 1. Composition API
All components use `<script setup>` with Composition API for better:
- Type inference
- Tree-shaking
- Code organization

#### 2. Reactive State Management
- Use `ref()` for primitive values
- Use `reactive()` for objects (when needed)
- Use `computed()` for derived state
- Avoid direct mutations

#### 3. Error Handling Strategy
- Centralized in API service
- User-friendly error messages
- Graceful degradation
- Retry logic for transient failures

#### 4. Loading States
- Always show loading indicators
- Skeleton screens for better UX
- Prevent duplicate requests

#### 5. Performance Optimization
- Lazy loading routes
- Computed properties for expensive operations
- Pagination for large datasets
- Image lazy loading
- Code splitting

### Code Quality Standards

#### Naming Conventions
- **Components**: PascalCase (`ProductCard.vue`)
- **Composables**: camelCase with `use` prefix (`useProducts.js`)
- **Utilities**: camelCase (`formatDate.js`)
- **Constants**: UPPER_SNAKE_CASE (`STORAGE_KEYS`)

#### Import Organization
```javascript
// 1. Vue core
import { ref, computed, onMounted } from 'vue';

// 2. Vue ecosystem
import { useRouter } from 'vue-router';
import { useHead } from '@vueuse/head';

// 3. Composables
import { useProducts, useCompare } from '@/composables';

// 4. Services
import { apiService } from '@/services/api';

// 5. Utils
import { formatDate, getImageUrl } from '@/utils';

// 6. Components
import ProductCard from '@/components/ProductCard.vue';
```

#### JSDoc Comments
All composables and utilities include JSDoc comments for:
- Type information
- Parameter descriptions
- Return value descriptions
- Usage examples

### Migration Notes

When migrating from Blade to Vue:
- Move business logic to composables
- Use API service instead of direct axios calls
- Replace inline styles with Tailwind classes
- Use Vue Router for navigation
- Implement proper loading and error states

### Performance Considerations

1. **Lazy Loading**: Routes and heavy components
2. **Pagination**: Large datasets
3. **Computed Properties**: Expensive calculations
4. **Debouncing**: Search inputs
5. **Image Optimization**: Lazy loading, proper sizing
6. **Code Splitting**: Route-based splitting

### Security

1. **CSRF Protection**: Automatic token injection
2. **XSS Prevention**: Vue's built-in escaping
3. **Input Validation**: Client and server-side
4. **Rate Limiting**: API level

### Accessibility

1. **Semantic HTML**: Proper element usage
2. **ARIA Labels**: Screen reader support
3. **Keyboard Navigation**: Tab order, shortcuts
4. **Focus Management**: Visible focus indicators
