/**
 * Products Store
 * Manages products state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Product } from '../types/index';

export const useProductsStore = createBaseStore<Product>('products', adminApi.products, {
  extraActions: {
    duplicateItem: async (storeState, id: number | string) => {
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
    importProducts: async (
      storeState,
      file: File,
      delimiter: string = ',',
      hasHeader: boolean = true
    ) => {
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

