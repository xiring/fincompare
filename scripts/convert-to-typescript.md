# TypeScript Conversion Helper

This guide helps convert remaining JavaScript files to TypeScript.

## Pattern for API Modules

**Template:**
```typescript
/**
 * [Resource] API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils'; // Only if file uploads
import type { AxiosResponse } from 'axios';
import type { [Resource], PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<[Resource]> | [Resource][]>> =>
    apiClient.get('/[resource]', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: [Resource] } | [Resource]>> =>
    apiClient.get(`/[resource]/${id}`),
  create: (data: Partial<[Resource]>): Promise<AxiosResponse<{ data: [Resource] } | [Resource]>> => {
    // If file uploads:
    const formData = toFormData(data as Record<string, any>, ['field1', 'field2']);
    return apiClient.post('/[resource]', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    // Otherwise:
    return apiClient.post('/[resource]', data);
  },
  update: (id: number | string, data: Partial<[Resource]>): Promise<AxiosResponse<{ data: [Resource] } | [Resource]>> => {
    // Similar to create
  },
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/[resource]/${id}`),
};
```

## Pattern for Stores

**Template:**
```typescript
/**
 * [Resource] Store
 * Manages [resource] state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { [Resource] } from '../types/index';

export const use[Resource]Store = createBaseStore<[Resource]>('[resource]', adminApi.[resource]);
```

## Remaining Files to Convert

### API Modules (`.js` → `.ts`)
- [ ] attributes.js
- [ ] blogs.js
- [ ] cmsPages.js
- [ ] faqs.js
- [ ] forms.js
- [ ] leads.js
- [ ] permissions.js
- [ ] productCategories.js
- [ ] profile.js
- [ ] roles.js
- [ ] settings.js
- [ ] uploads.js
- [ ] activity.js

### Stores (`.js` → `.ts`)
- [ ] activity.js
- [ ] attributes.js
- [ ] auth.js
- [ ] blogs.js
- [ ] cmsPages.js
- [ ] faqs.js
- [ ] forms.js
- [ ] leads.js
- [ ] permissions.js
- [ ] productCategories.js
- [ ] roles.js
- [ ] settings.js

## Quick Conversion Steps

1. Copy the `.js` file to `.ts`
2. Add type imports
3. Add type annotations to function parameters and return types
4. Update imports to use `.ts` extensions where needed
5. Test with `pnpm run type-check`

