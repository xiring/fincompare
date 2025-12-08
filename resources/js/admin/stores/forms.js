/**
 * Forms Store
 * Manages forms state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useFormsStore = createBaseStore('forms', adminApi.forms, {
  extraActions: {
    duplicateItem: async (storeState, id) => {
      storeState.loading.value = true;
      storeState.error.value = null;
      try {
        const response = await adminApi.forms.duplicate(id);
        return response.data;
      } catch (err) {
        storeState.error.value = err;
        throw err;
      } finally {
        storeState.loading.value = false;
      }
    },
  },
});
