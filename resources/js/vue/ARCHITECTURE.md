# Vue SPA Architecture Documentation

## Overview

This document describes the senior-level architecture of the Vue.js Single Page Application.

## Architecture Layers

### 1. Service Layer (`services/`)
**Purpose**: Centralized API communication

- **`api.js`**: Main API service with axios instance
  - Request/response interceptors
  - CSRF token handling
  - Error handling
  - Type-safe API methods

**Usage**:
```javascript
import { apiService } from '@/services/api';
const response = await apiService.getProducts({ page: 1 });
```

### 2. Composables Layer (`composables/`)
**Purpose**: Reusable business logic and state management

#### Core Composables

- **`useAsyncData`**: Generic async data fetching
  - Loading states
  - Error handling
  - Retry logic
  - Caching support

- **`usePagination`**: Pagination state management
  - Page tracking
  - Infinite scroll support
  - Next/previous navigation

- **`useLocalStorage`**: Reactive localStorage
  - Automatic sync
  - Type-safe storage
  - Reactive updates

#### Feature-Specific Composables

- **`useHomeData`**: Home page data fetching
- **`useProducts`**: Products with filtering and pagination
- **`useProduct`**: Single product data
- **`useBlog`**: Blog posts with filtering
- **`useCompare`**: Product comparison functionality
- **`useCompareData`**: Compare page data management
- **`useSiteSettings`**: Site settings with caching

### 3. Utilities Layer (`utils/`)
**Purpose**: Pure functions and constants

- **`constants.js`**: Application constants
- **`helpers.js`**: Utility functions (formatting, URLs, etc.)
- **`validation.js`**: Form validation helpers

### 4. Components Layer (`components/`)
**Purpose**: Reusable UI components

- Single responsibility principle
- Props validation
- Event emission
- Composition API preferred

### 5. Pages Layer (`pages/`)
**Purpose**: Route-level components

- Use composables for data
- Minimal business logic
- Focus on presentation

## Design Patterns

### 1. Composition API
All components use `<script setup>` with Composition API for better:
- Type inference
- Tree-shaking
- Code organization

### 2. Reactive State Management
- Use `ref()` for primitive values
- Use `reactive()` for objects (when needed)
- Use `computed()` for derived state
- Avoid direct mutations

### 3. Error Handling Strategy
- Centralized in API service
- User-friendly error messages
- Graceful degradation
- Retry logic for transient failures

### 4. Loading States
- Always show loading indicators
- Skeleton screens for better UX
- Prevent duplicate requests

### 5. Performance Optimization
- Lazy loading routes
- Computed properties for expensive operations
- Pagination for large datasets
- Image lazy loading
- Code splitting

## Code Quality Standards

### 1. Naming Conventions
- **Components**: PascalCase (`ProductCard.vue`)
- **Composables**: camelCase with `use` prefix (`useProducts.js`)
- **Utilities**: camelCase (`formatDate.js`)
- **Constants**: UPPER_SNAKE_CASE (`STORAGE_KEYS`)

### 2. File Organization
```
vue/
├── components/     # Reusable components
├── composables/    # Business logic
├── layouts/        # Layout components
├── pages/          # Route components
├── services/       # API layer
├── stores/         # Global state (Pinia)
└── utils/          # Utilities
```

### 3. Import Organization
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

### 4. JSDoc Comments
All composables and utilities include JSDoc comments for:
- Type information
- Parameter descriptions
- Return value descriptions
- Usage examples

## Best Practices

### 1. Data Fetching
✅ **DO**:
```javascript
const { data, loading, error, fetchData } = useAsyncData(fetcher);
await fetchData();
```

❌ **DON'T**:
```javascript
const data = ref(null);
axios.get('/api/data').then(res => data.value = res.data);
```

### 2. State Management
✅ **DO**:
```javascript
const count = computed(() => items.value.length);
```

❌ **DON'T**:
```javascript
const count = ref(0);
watch(items, () => count.value = items.value.length);
```

### 3. Error Handling
✅ **DO**:
```javascript
try {
  await fetchData();
} catch (err) {
  showError('Failed to load data');
}
```

❌ **DON'T**:
```javascript
fetchData().catch(() => {});
```

### 4. Component Props
✅ **DO**:
```javascript
const props = defineProps({
  product: {
    type: Object,
    required: true,
    validator: (value) => value.id != null
  }
});
```

❌ **DON'T**:
```javascript
const props = defineProps(['product']);
```

## Testing Strategy

### Unit Tests
- Test composables in isolation
- Mock API calls
- Test error scenarios

### Component Tests
- Test user interactions
- Test prop validation
- Test event emission

### E2E Tests
- Critical user flows
- Form submissions
- Navigation

## Performance Considerations

1. **Lazy Loading**: Routes and heavy components
2. **Pagination**: Large datasets
3. **Computed Properties**: Expensive calculations
4. **Debouncing**: Search inputs
5. **Image Optimization**: Lazy loading, proper sizing
6. **Code Splitting**: Route-based splitting

## Security

1. **CSRF Protection**: Automatic token injection
2. **XSS Prevention**: Vue's built-in escaping
3. **Input Validation**: Client and server-side
4. **Rate Limiting**: API level

## Accessibility

1. **Semantic HTML**: Proper element usage
2. **ARIA Labels**: Screen reader support
3. **Keyboard Navigation**: Tab order, shortcuts
4. **Focus Management**: Visible focus indicators

## Future Improvements

1. **TypeScript Migration**: Better type safety
2. **Unit Tests**: Comprehensive test coverage
3. **E2E Tests**: Critical path testing
4. **Performance Monitoring**: Real user metrics
5. **Error Tracking**: Sentry integration
6. **Analytics**: User behavior tracking

