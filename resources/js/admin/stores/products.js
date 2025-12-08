/**
 * Products Store
 * Manages products state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useProductsStore = createBaseStore('products', adminApi.products, {
  extraActions: {
    duplicateItem: async (storeState, id) => {
      storeState.loading.value = true;
      storeState.error.value = null;
      try {
        const response = await adminApi.products.duplicate(id);
        return response.data;
      } catch (err) {
        storeState.error.value = err;
        throw err;
      } finally {
        storeState.loading.value = false;
      }
    },
    importProducts: async (storeState, file, delimiter = ',', hasHeader = true) => {
      storeState.loading.value = true;
      storeState.error.value = null;
      try {
        const response = await adminApi.products.import(file, delimiter, hasHeader);
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
