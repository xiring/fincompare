# Admin Stores (Pinia)

## Overview

Pinia stores provide centralized state management for the admin panel. Each resource has its own store that handles data fetching, caching, and state updates.

## Available Stores

- `useAuthStore` - User authentication and profile
- `useProductsStore` - Products management
- `usePartnersStore` - Partners management
- `useProductCategoriesStore` - Product categories
- `useAttributesStore` - Product attributes
- `useUsersStore` - User management
- `useRolesStore` - Role management
- `usePermissionsStore` - Permission management
- `useLeadsStore` - Leads management
- `useFormsStore` - Forms management
- `useBlogsStore` - Blog posts
- `useCmsPagesStore` - CMS pages
- `useFaqsStore` - FAQs
- `useSettingsStore` - Site settings
- `useActivityStore` - Activity logs

## Usage

### Basic Example

```vue
<script setup>
import { onMounted } from 'vue';
import { useProductsStore } from '@/stores';

const productsStore = useProductsStore();

onMounted(async () => {
  // Fetch products
  await productsStore.fetchItems({ per_page: 20 });
  
  // Access state
  console.log(productsStore.items);
  console.log(productsStore.loading);
  console.log(productsStore.error);
});
</script>

<template>
  <div v-if="productsStore.loading">Loading...</div>
  <div v-else>
    <div v-for="product in productsStore.items" :key="product.id">
      {{ product.name }}
    </div>
  </div>
</template>
```

### CRUD Operations

```javascript
import { useProductsStore } from '@/stores';

const productsStore = useProductsStore();

// Create
await productsStore.createItem({ name: 'New Product', price: 100 });

// Update
await productsStore.updateItem(1, { name: 'Updated Product' });

// Delete
await productsStore.deleteItem(1);

// Fetch single item
const product = await productsStore.fetchItem(1);
console.log(productsStore.currentItem);
```

### Using with Components

```vue
<script setup>
import { computed } from 'vue';
import { useProductsStore } from '@/stores';
import { extractValidationErrors } from '@/utils/validation';

const productsStore = useProductsStore();
const errors = ref({});

const handleSubmit = async () => {
  try {
    await productsStore.createItem(formData);
    // Success - redirect or show message
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    }
  }
};

// Computed properties for reactive access
const isLoading = computed(() => productsStore.loading);
const products = computed(() => productsStore.items);
</script>
```

## Store Structure

Each store typically provides:

- **State:**
  - `items` - Array of items (for list resources)
  - `currentItem` - Currently selected item
  - `loading` - Loading state
  - `error` - Error state
  - `pagination` - Pagination metadata (if applicable)

- **Actions:**
  - `fetchItems(params)` - Fetch list of items
  - `fetchItem(id)` - Fetch single item
  - `createItem(data)` - Create new item
  - `updateItem(id, data)` - Update existing item
  - `deleteItem(id)` - Delete item
  - `clearCurrentItem()` - Clear current item from state

## Benefits

1. **Centralized State** - Single source of truth for each resource
2. **Automatic Caching** - Data is cached in the store
3. **Reactive Updates** - Vue components automatically update when store state changes
4. **Error Handling** - Centralized error state management
5. **Loading States** - Built-in loading state management
6. **Type Safety** - Consistent API across all stores

## Best Practices

1. Use stores for data that needs to be shared across components
2. Clear current item when navigating away from detail pages
3. Handle errors appropriately in components
4. Use computed properties to access store state reactively
5. Don't mutate store state directly - use actions instead

