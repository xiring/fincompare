/**
 * Roles Store
 * Manages roles state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useRolesStore = createBaseStore('roles', adminApi.roles, {
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item);
  },
});
