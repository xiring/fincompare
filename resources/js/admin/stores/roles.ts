/**
 * Roles Store
 * Manages roles state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Role } from '../types/index';

export const useRolesStore = createBaseStore<Role>('roles', adminApi.roles, {
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item as Role);
  },
});

