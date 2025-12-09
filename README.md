# FinCompare

A comprehensive Laravel application for managing partners, product categories, products, leads, and dynamic forms. Features include queued product CSV import, background processing with Horizon, telescoped debugging, and a full-featured admin panel built with Vue 3 and TypeScript.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Quick Start](#quick-start)
- [Project Structure](#project-structure)
- [Admin Panel](#admin-panel)
- [Public SPA](#public-spa)
- [Modules & Features](#modules--features)
- [Development](#development)
- [Testing](#testing)
- [Deployment](#deployment)
- [Documentation](#documentation)

## Features

### Core Functionality
- **Partners Management**: CRUD operations with logo upload
- **Product Categories**: CRUD with image upload and dynamic form associations
- **Products**: Full CRUD with image upload, CSV import, and dynamic attributes
- **Attributes**: Typed attribute system (text, number, percentage, boolean, JSON) per category
- **Dynamic Forms**: Pre-form and post-form system for product categories
- **Leads Management**: Lead intake, tracking, and CSV export
- **Content Management**: Blog posts and CMS pages with SEO fields
- **RBAC**: Role-based access control using Spatie Permissions
- **Activity Logging**: Comprehensive audit trail

### Technical Features
- **Queue System**: Redis-based queued jobs for CSV imports
- **Background Processing**: Laravel Horizon for queue monitoring
- **Debugging Tools**: Laravel Telescope for request/query debugging
- **Type Safety**: Full TypeScript migration for frontend
- **SPA Architecture**: Vue 3 SPA for public-facing pages
- **Admin Panel**: Vue 3 + TypeScript admin interface
- **File Uploads**: Image uploads for partners, products, categories, and blog posts
- **WYSIWYG Editor**: Rich text editing with image upload support

## Tech Stack

### Backend
- **PHP** >= 8.2
- **Laravel** 12
- **Database**: MySQL/PostgreSQL (app-agnostic), SQLite for tests
- **Queue**: Redis
- **Cache**: Redis
- **RBAC**: spatie/laravel-permission
- **Audit**: spatie/laravel-activitylog
- **Testing**: PestPHP

### Frontend
- **Vue 3** (Composition API)
- **TypeScript** (Full type safety)
- **Vite** (Build tool)
- **TailwindCSS** (Styling)
- **Pinia** (State management)
- **Vue Router** (Routing)
- **Axios** (HTTP client)

### Development Tools
- **Horizon** (Queue monitoring, admin-gated)
- **Telescope** (Debugging, admin-gated)
- **Debugbar** (Development debugging)

## Quick Start

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js >= 18
- pnpm (install via `npm install -g pnpm` or see [pnpm installation guide](https://pnpm.io/installation))
- Redis
- MySQL/PostgreSQL

### Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd fincompare
```

2. **Install dependencies**
```bash
composer install
pnpm install
```

> **Note**: This project uses `pnpm` as the package manager. If you have `package-lock.json` from a previous npm installation, you can remove it. The `pnpm-lock.yaml` file should be committed to version control.

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure:
- Database connection (`DB_*`)
- Redis connection (`REDIS_*`)
- Application URL (`APP_URL`)
- Queue connection: `QUEUE_CONNECTION=redis`

4. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build frontend assets**
```bash
# Production build (fast, skips type checking)
pnpm run build

# Production build with type checking (slower, recommended for CI/CD)
pnpm run build:check

# Development server
pnpm run dev

# Type checking only
pnpm run type-check
```

6. **Start the application**
```bash
# Start Laravel server
php artisan serve

# Start queue worker (in separate terminal)
php artisan horizon
# or
php artisan queue:work --queue=imports,default
```

7. **Access the application**
- Public site: `http://localhost:8000`
- Admin panel: `http://localhost:8000/admin`
- Horizon: `http://localhost:8000/horizon` (admin role required)
- Telescope: `http://localhost:8000/telescope` (admin role required)

## Project Structure

```
fincompare/
├── src/                          # Domain-driven architecture
│   ├── Auth/                    # Authentication & authorization
│   ├── Catalog/                 # Products, categories, attributes
│   ├── Content/                 # Blog posts, CMS pages
│   ├── Forms/                   # Dynamic forms system
│   ├── Leads/                   # Lead management
│   ├── Partners/                # Partner management
│   └── Shared/                  # Shared utilities
├── resources/
│   ├── js/
│   │   ├── admin/              # Admin panel (Vue 3 + TypeScript)
│   │   │   ├── components/     # Reusable components
│   │   │   ├── pages/          # Page components
│   │   │   ├── stores/         # Pinia stores
│   │   │   ├── services/       # API services
│   │   │   ├── types/          # TypeScript types
│   │   │   └── utils/          # Utilities
│   │   └── public/             # Public SPA (Vue 3 + TypeScript)
│   │       ├── components/     # Reusable components
│   │       ├── pages/          # Page components
│   │       ├── composables/    # Composition API composables
│   │       ├── stores/         # Pinia stores
│   │       └── services/       # API services
│   └── views/                  # Blade templates
├── routes/
│   ├── web.php                 # Public web routes
│   ├── api.php                 # API routes
│   ├── auth.php                # Authentication routes
│   └── admin.php               # Admin routes
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
└── docs/                       # Project documentation
```

## Admin Panel

The admin panel is a Vue 3 + TypeScript Single Page Application located in `resources/js/admin/`.

### Architecture

- **TypeScript**: Full type safety across the application
- **Component-based**: Reusable Vue components
- **State Management**: Pinia stores for global state
- **API Layer**: Centralized API service with type-safe methods
- **Form Handling**: Unified create/edit forms with proper file upload support

### Key Features

#### Unified Form Components
All entities use unified `Form.vue` components that handle both create and edit modes:
- Products (`resources/js/admin/pages/products/Form.vue`)
- Partners (`resources/js/admin/pages/partners/Form.vue`)
- Product Categories (`resources/js/admin/pages/product-categories/Form.vue`)
- Blogs (`resources/js/admin/pages/blogs/Form.vue`)
- And more...

#### Form Submission Pattern
**Important**: Always pass plain JavaScript objects to API modules, not `FormData`. The API modules handle file upload conversion automatically.

```typescript
// ✅ Correct
const data = {
  name: form.name,
  image: form.image, // File object
};
await store.createItem(data);

// ❌ Incorrect
const formData = new FormData();
formData.append('name', form.name);
await store.createItem(formData); // API module expects plain object
```

#### File Uploads
File uploads are handled automatically by API modules using the `toFormData` utility:
- Partners: Logo upload
- Products: Image upload
- Product Categories: Image upload
- Blogs: Featured image upload

### Admin Modules

1. **Dashboard**: Overview with statistics and recent leads
2. **Partners**: CRUD with logo upload
3. **Product Categories**: CRUD with image upload and form associations
4. **Attributes**: Dynamic attribute management per category
5. **Products**: CRUD with CSV import and dynamic attributes
6. **Forms**: Dynamic form builder (pre-form/post-form)
7. **Blogs**: Content management with featured images
8. **CMS Pages**: Static page management
9. **Leads**: Lead management and CSV export
10. **Users/Roles/Permissions**: RBAC management
11. **Activity Log**: Audit trail viewer
12. **Settings**: Site configuration

## Public SPA

The public-facing site is a Vue 3 SPA located in `resources/js/public/`.

### Architecture Principles

1. **Service Layer**: Centralized API client with interceptors
2. **Composables**: Reusable business logic with Composition API
3. **State Management**: Pinia stores for global state
4. **Type Safety**: TypeScript for type checking
5. **Performance**: Lazy loading, pagination, code splitting

### Key Composables

- `useAsyncData`: Generic async data fetching
- `usePagination`: Pagination state management
- `useCompare`: Product comparison functionality
- `useLocalStorage`: Reactive localStorage
- `useProducts`: Product data fetching
- `useSEO`: SEO meta tag management

### Best Practices

See the [Vue SPA Architecture](#vue-spa-architecture) section below for detailed best practices.

## Modules & Features

### Catalog Module
- **Partners**: Organization management with logo upload
- **Product Categories**: Category management with image upload and form associations
- **Attributes**: Typed attributes (text, number, percentage, boolean, JSON) per category
- **Products**: Product management with:
  - Image upload
  - Dynamic attribute values
  - CSV import (queued)
  - Featured products

### Forms Module
- **Dynamic Forms**: Create pre-form and post-form types
- **Form Inputs**: Text, textarea, dropdown, checkbox inputs
- **Category Association**: Link forms to product categories
- **Validation**: Custom validation rules per input

### Content Module
- **Blog Posts**: Full CRUD with:
  - Featured image upload
  - SEO fields (title, description, keywords)
  - WYSIWYG content editor
  - Categories and tags
- **CMS Pages**: Static page management with SEO fields

### Leads Module
- **Lead Management**: Track and manage leads
- **CSV Export**: Export leads to CSV
- **Status Tracking**: Lead status management
- **Product Association**: Link leads to products/categories

### Auth Module
- **Authentication**: Login/logout
- **Profile Management**: User profile editing
- **RBAC**: Role-based access control
- **User Management**: Admin user management

## Development

### Common Commands

```bash
# Clear caches
php artisan route:clear && php artisan config:clear && php artisan cache:clear

# Type checking (TypeScript)
pnpm run type-check

# Production build (fast, skips type checking)
pnpm run build

# Production build with type checking (slower, for CI/CD)
pnpm run build:check

# Development server
pnpm run dev

# Run tests
php artisan test

# Generate docblocks
php scripts/generate_docblocks.php
```

### Queues & Horizon

Horizon is configured for queue monitoring and is gated to admin users.

```bash
# Start Horizon
php artisan horizon

# Or use queue worker directly
php artisan queue:work --queue=imports,default
```

Access Horizon at `/horizon` (requires admin role).

### Product CSV Import

1. Navigate to Admin → Products → Import
2. Upload CSV file with required headers:
   - Required: `name`, `partner_id`, `product_category_id`
   - Optional: `slug`, `description`, `is_featured`, `status`, `attributes`
3. Import is queued on `imports` queue
4. Monitor progress via Horizon

Sample CSV: `resources/examples/product_import_sample.csv`

### TypeScript Development

The project uses TypeScript for type safety. Key points:

- All Vue components use `<script setup lang="ts">`
- Type definitions in `resources/js/types/` and module-specific type folders
- API modules are fully typed
- Stores use generic types for type safety

See `docs/typescript-migration.md` for detailed TypeScript patterns.

## Testing

### Setup

Tests use PestPHP with in-memory SQLite.

Optional `.env.testing` configuration:
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
MAIL_MAILER=array
TELESCOPE_ENABLED=false
```

### Running Tests

```bash
php artisan test
# or
./vendor/bin/pest
```

## Deployment

### Environment Setup

1. Set `APP_ENV=production`
2. Configure production database
3. Set `QUEUE_CONNECTION=redis`
4. Run migrations: `php artisan migrate --force`
5. Build assets: `pnpm run build` (production build, skips type checking for speed)
6. Cache configuration: `php artisan config:cache`
7. Cache routes: `php artisan route:cache`

### Queue Workers

Ensure queue workers are running:
```bash
php artisan horizon
# or use supervisor/systemd for production
```

### Storage

Ensure storage is linked:
```bash
php artisan storage:link
```

### Admin Tools

Horizon and Telescope are enabled in production but gated to admin users:
- Horizon: `/horizon`
- Telescope: `/telescope`

## Documentation

Comprehensive documentation is available in the `docs/` directory:

- **Architecture**: `docs/architecture.md`
- **Configuration**: `docs/configuration.md`
- **API Routes**: `docs/api.md`
- **Modules**: `docs/modules.md`
- **Data Model**: `docs/data-model.md`
- **Controllers**: `docs/controllers.md`
- **CSV Import**: `docs/csv-import.md`
- **Deployment**: `docs/deployment.md`
- **Ubuntu 24.04 Deployment**: `docs/deployment-ubuntu.md` (Complete guide for Nginx, PHP 8.3-FPM, PostgreSQL, Redis)
- **TypeScript Migration**: `docs/typescript-migration.md`
- **Contributing**: `docs/contributing.md`
- **Troubleshooting**: `docs/troubleshooting.md`

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
├── types/              # TypeScript type definitions
├── app.ts              # Application entry point
├── App.vue             # Root component
└── router.ts           # Vue Router configuration
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
- Props validation with TypeScript
- Emit events properly
- Composition API preferred

### Key Composables

#### `useAsyncData`
Generic async data fetching with loading, error, and retry logic.

```typescript
const { data, loading, error, execute, refresh } = useAsyncData(fetcher, options);
```

#### `usePagination`
Pagination state management for infinite scroll and paginated lists.

```typescript
const { currentPage, hasMore, loadMore, reset } = usePagination(options);
```

#### `useLocalStorage`
Reactive localStorage with automatic sync.

```typescript
const { value, remove } = useLocalStorage('key', defaultValue);
```

#### `useCompare`
Product comparison functionality with localStorage sync.

```typescript
const { compareIds, toggleCompare, isInCompare, clearAll } = useCompare();
```

### API Service

All API calls go through the centralized service:

```typescript
import { apiService } from '@/services/api';

// Usage
const response = await apiService.getProducts({ page: 1 });
const product = await apiService.getProduct(slug);
```

### Best Practices

#### 1. Data Fetching
**Always use composables for data fetching** - Don't call API directly in components

```typescript
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

```typescript
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

```typescript
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

```vue
<!-- ✅ Good: Loading skeleton + progress bar -->
<div v-if="loading" class="skeleton-loader">...</div>
<div v-else>...</div>
```

#### 5. Type Safety
**Use TypeScript** - Full type safety for better IDE support and error catching

```typescript
interface Product {
  id: number;
  name: string;
  price: number;
}

const product = ref<Product | null>(null);
```

#### 6. Performance Optimization
**Use computed properties, lazy loading, and pagination**

```typescript
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
- **Props Validation**: Always define prop types with TypeScript
- **Event Naming**: Use kebab-case for events (`@update:value`)
- **Composition API**: Prefer `<script setup lang="ts">` over Options API

#### 9. State Management
- **Local State**: Use `ref`/`reactive` for component-specific state
- **Shared State**: Use Pinia stores for global state
- **Server State**: Use composables that sync with API

#### 10. Code Organization
- **File Naming**: Use PascalCase for components (`ProductCard.vue`)
- **Folder Structure**: Group related files together
- **Barrel Exports**: Use `index.ts` for clean imports
- **Separation of Concerns**: Keep business logic in composables, not components

### Design Patterns

#### 1. Composition API
All components use `<script setup lang="ts">` with Composition API for better:
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
- **Composables**: camelCase with `use` prefix (`useProducts.ts`)
- **Utilities**: camelCase (`formatDate.ts`)
- **Constants**: UPPER_SNAKE_CASE (`STORAGE_KEYS`)

#### Import Organization
```typescript
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

// 7. Types
import type { Product } from '@/types';
```

#### TypeScript Types
All composables and utilities include TypeScript types for:
- Type information
- Parameter descriptions
- Return value descriptions
- Better IDE support

### Migration Notes

When migrating from Blade to Vue:
- Move business logic to composables
- Use API service instead of direct axios calls
- Replace inline styles with Tailwind classes
- Use Vue Router for navigation
- Implement proper loading and error states
- Add TypeScript types

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

---

## License

[Your License Here]

## Contributing

Please see [CONTRIBUTING.md](docs/contributing.md) for details on our code of conduct and the process for submitting pull requests.
