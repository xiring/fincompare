# TypeScript Migration Guide

This document outlines the TypeScript migration for the FinCompare application, covering both admin and public-facing applications.

## Overview

The codebase has been migrated to TypeScript to provide:
- **Type Safety**: Catch errors at compile time
- **Better IDE Support**: Autocomplete, refactoring, and navigation
- **Improved Documentation**: Types serve as inline documentation
- **Easier Refactoring**: Safer code changes with type checking

## Migration Status

### ✅ Completed

1. **TypeScript Configuration**
   - `tsconfig.json` - Root TypeScript configuration
   - Type definitions in `resources/js/types/`
   - Admin-specific types in `resources/js/admin/types/`
   - Public-specific types in `resources/js/public/types/`

2. **Core Infrastructure**
   - Entry points: `admin/app.ts`, `public/app.ts`
   - Routers: `admin/router.ts`, `public/router.ts`
   - API Client: `admin/services/api/client.ts`
   - Base Store Factory: `admin/stores/utils/baseStore.ts`
   - Pagination Utility: `admin/stores/utils/pagination.ts`
   - API Utils: `admin/services/api/utils.ts`

3. **Example Conversions**
   - Products Store: `admin/stores/products.ts`
   - Products API Module: `admin/services/api/modules/products.ts`
   - API Index: `admin/services/api/index.ts`

### 🔄 In Progress / To Do

1. **Remaining Stores** (Convert `.js` to `.ts`)
   - `admin/stores/*.js` → `admin/stores/*.ts`
   - `public/stores/*.js` → `public/stores/*.ts`

2. **Remaining API Modules** (Convert `.js` to `.ts`)
   - `admin/services/api/modules/*.js` → `admin/services/api/modules/*.ts`

3. **Composables** (Convert `.js` to `.ts`)
   - `admin/composables/*.js` → `admin/composables/*.ts`
   - `public/composables/*.js` → `public/composables/*.ts`

4. **Utilities** (Convert `.js` to `.ts`)
   - `admin/utils/*.js` → `admin/utils/*.ts`
   - `public/utils/*.js` → `public/utils/*.ts`

5. **Vue Components** (Add `<script setup lang="ts">`)
   - All `.vue` files should use TypeScript in `<script setup>` blocks

## Type Definitions

### Shared Types (`resources/js/types/index.d.ts`)

- `ApiResponse<T>` - Generic API response wrapper
- `PaginatedResponse<T>` - Paginated API response
- `PaginationMeta` - Pagination metadata
- `BaseEntity` - Base entity interface with `id`, `created_at`, `updated_at`
- `FormErrors` - Form validation errors
- `RouteMeta` - Router meta information

### Admin Types (`resources/js/admin/types/index.d.ts`)

- `User`, `Role`, `Permission` - Auth entities
- `Product`, `ProductCategory`, `Attribute` - Catalog entities
- `Partner` - Partner entity
- `Form`, `FormInput` - Form entities
- `Lead` - Lead entity
- `BlogPost`, `CmsPage`, `Faq` - Content entities
- `ActivityLog` - Activity log entity
- `SiteSetting` - Settings entity
- `DashboardStats` - Dashboard statistics
- `StoreState<T>`, `StoreActions<T>` - Store type helpers

### Public Types (`resources/js/public/types/index.d.ts`)

- Re-exports of common admin types
- `SiteSettings` - Public site settings
- `Toast` - Toast notification
- `CompareItem` - Product comparison item
- `FormSubmissionData` - Form submission data

## Converting Files

### 1. JavaScript Files to TypeScript

**Before:**
```javascript
// stores/products.js
import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useProductsStore = createBaseStore('products', adminApi.products);
```

**After:**
```typescript
// stores/products.ts
import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';
import type { Product } from '../types';

export const useProductsStore = createBaseStore<Product>('products', adminApi.products);
```

### 2. Vue Components

**Before:**
```vue
<script setup>
import { ref } from 'vue';

const count = ref(0);
</script>
```

**After:**
```vue
<script setup lang="ts">
import { ref } from 'vue';

const count = ref<number>(0);
</script>
```

### 3. API Modules

**Before:**
```javascript
// modules/products.js
import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/products', { params }),
  show: (id) => apiClient.get(`/products/${id}`),
};
```

**After:**
```typescript
// modules/products.ts
import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Product, PaginatedResponse } from '../../../types';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Product>>> =>
    apiClient.get('/products', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Product }>> =>
    apiClient.get(`/products/${id}`),
};
```

### 4. Composables

**Before:**
```javascript
// composables/useProducts.js
import { ref, computed } from 'vue';

export function useProducts() {
  const products = ref([]);
  const loading = ref(false);
  
  return { products, loading };
}
```

**After:**
```typescript
// composables/useProducts.ts
import { ref, computed, type Ref } from 'vue';
import type { Product } from '../types';

export function useProducts() {
  const products = ref<Product[]>([]);
  const loading = ref<boolean>(false);
  
  return { products, loading };
}
```

## TypeScript Configuration

The `tsconfig.json` includes:

- **Strict Mode**: Enabled for maximum type safety
- **Path Aliases**: `@/*`, `@admin/*`, `@public/*`
- **Vue Support**: Proper handling of `.vue` files
- **Modern ES Features**: ES2020 target with ESNext modules

## Building and Type Checking

### Type Check
```bash
npm run type-check
```

### Build (includes type checking)
```bash
npm run build
```

### Development (with type checking)
```bash
npm run dev
```

## Common Patterns

### 1. Store with Custom Actions

```typescript
import { createBaseStore } from './utils/baseStore';
import type { Product } from '../types';

export const useProductsStore = createBaseStore<Product>('products', adminApi.products, {
  extraActions: {
    duplicateItem: async (storeState, id: number | string) => {
      // Implementation
    },
  },
});
```

### 2. Component Props

```vue
<script setup lang="ts">
interface Props {
  title: string;
  count?: number;
  items: string[];
}

const props = withDefaults(defineProps<Props>(), {
  count: 0,
});
</script>
```

### 3. Component Emits

```vue
<script setup lang="ts">
interface Emits {
  (e: 'update', value: string): void;
  (e: 'delete', id: number): void;
}

const emit = defineEmits<Emits>();
</script>
```

### 4. Router Navigation

```typescript
import { useRouter } from 'vue-router';

const router = useRouter();
router.push({ name: 'admin.products.index', query: { page: 1 } });
```

## Migration Checklist

For each file to migrate:

- [ ] Rename `.js` to `.ts` (or add `lang="ts"` to Vue components)
- [ ] Add type annotations to function parameters and return types
- [ ] Add type annotations to variables where type cannot be inferred
- [ ] Import types from `types/` directories
- [ ] Fix any type errors
- [ ] Test the functionality
- [ ] Update imports in dependent files

## Troubleshooting

### Common Errors

1. **"Cannot find module"**
   - Check path aliases in `tsconfig.json`
   - Ensure file extensions are correct

2. **"Property does not exist on type"**
   - Add proper type definitions
   - Use type assertions if necessary (`as Type`)

3. **"Implicit any type"**
   - Enable `noImplicitAny` in `tsconfig.json` (already enabled)
   - Add explicit type annotations

4. **Vue component type errors**
   - Ensure `*.vue` files are properly declared in `types/index.d.ts`
   - Use `lang="ts"` in `<script setup>` blocks

## Resources

- [TypeScript Handbook](https://www.typescriptlang.org/docs/handbook/intro.html)
- [Vue 3 TypeScript Guide](https://vuejs.org/guide/typescript/overview.html)
- [Pinia TypeScript Guide](https://pinia.vuejs.org/core-concepts/state.html#typescript)

