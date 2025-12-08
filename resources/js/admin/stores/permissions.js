/**
 * Permissions Store
 * Manages permissions state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const usePermissionsStore = createBaseStore('permissions', adminApi.permissions, {
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item);
  },
});
