# Vue SPA Architecture

## Overview

This Vue.js Single Page Application follows senior-level development practices with a clean, maintainable architecture.

## Directory Structure

```
vue/
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

## Architecture Principles

### 1. Service Layer (`services/`)
- Centralized API client with interceptors
- Consistent error handling
- Request/response transformation
- Type-safe API methods

### 2. Composables (`composables/`)
- Reusable business logic
- Separation of concerns
- Reactive state management
- Error handling built-in

### 3. Utilities (`utils/`)
- Pure functions
- Constants management
- Validation helpers
- Formatting utilities

### 4. Components
- Single responsibility
- Props validation
- Emit events properly
- Composition API preferred

## Key Composables

### `useAsyncData`
Generic async data fetching with loading, error, and retry logic.

```javascript
const { data, loading, error, execute, refresh } = useAsyncData(fetcher, options);
```

### `usePagination`
Pagination state management for infinite scroll and paginated lists.

```javascript
const { currentPage, hasMore, loadMore, reset } = usePagination(options);
```

### `useLocalStorage`
Reactive localStorage with automatic sync.

```javascript
const { value, remove } = useLocalStorage('key', defaultValue);
```

### `useCompare`
Product comparison functionality with localStorage sync.

```javascript
const { compareIds, toggleCompare, isInCompare, clearAll } = useCompare();
```

## API Service

All API calls go through the centralized service:

```javascript
import { apiService } from '@/services/api';

// Usage
const response = await apiService.getProducts({ page: 1 });
const product = await apiService.getProduct(slug);
```

## Best Practices

1. **Always use composables for data fetching** - Don't call API directly in components
2. **Use constants** - Don't hardcode strings/numbers
3. **Handle errors gracefully** - Show user-friendly messages
4. **Loading states** - Always show loading indicators
5. **Type safety** - Use JSDoc comments for better IDE support
6. **Performance** - Use computed properties, lazy loading, and pagination
7. **Accessibility** - Proper ARIA labels and keyboard navigation

## Migration Notes

When migrating from Blade to Vue:
- Move business logic to composables
- Use API service instead of direct axios calls
- Replace inline styles with Tailwind classes
- Use Vue Router for navigation
- Implement proper loading and error states

