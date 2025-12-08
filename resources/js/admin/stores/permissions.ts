/**
 * Permissions Store
 * Manages permissions state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Permission } from '../types/index';

export const usePermissionsStore = createBaseStore<Permission>('permissions', adminApi.permissions, {
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item as Permission);
  },
});

