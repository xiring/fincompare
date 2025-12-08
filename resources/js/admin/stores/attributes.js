/**
 * Attributes Store
 * Manages attributes state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useAttributesStore = createBaseStore('attributes', adminApi.attributes, {
  onFetchItem: async ({ state, apiModule, id }) => {
    state.loading.value = true;
    state.error.value = null;
    try {
      // Attributes use 'edit' endpoint instead of 'show'
      const response = await apiModule.edit(id);
      const itemData = response.data?.data || response.data;
      state.currentItem.value = itemData;
      return state.currentItem.value;
    } catch (err) {
      state.error.value = err;
      throw err;
    } finally {
      state.loading.value = false;
    }
  },
  onFetchItems: async ({ state, apiModule, params, extractPagination }) => {
    state.loading.value = true;
    state.error.value = null;
    try {
      const response = await apiModule.index(params);
      const data = response.data;
      state.items.value = data.data || data || [];
      // Optimized: Mutate existing pagination ref instead of creating new object
      extractPagination(data, state.pagination.value);
      return state.items.value;
    } catch (err) {
      state.error.value = err;
      throw err;
    } finally {
      state.loading.value = false;
    }
  },
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item);
  },
  extraActions: {
    fetchByCategory: async (storeState, categoryId) => {
      storeState.loading.value = true;
      storeState.error.value = null;
      try {
        const response = await adminApi.attributes.byCategory(categoryId);
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
