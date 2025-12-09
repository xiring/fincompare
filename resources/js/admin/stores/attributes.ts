/**
 * Attributes Store
 * Manages attributes state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Attribute } from '../types/index';

export const useAttributesStore = createBaseStore<Attribute>('attributes', adminApi.attributes, {
  onFetchItem: async ({ state, apiModule, id }) => {
    state.loading.value = true;
    state.error.value = null;
    try {
      // Attributes use 'edit' endpoint instead of 'show'
      if (!apiModule.edit) {
        throw new Error('API module does not have edit method');
      }
      const response = await apiModule.edit(id);
      const itemData = (response.data as any)?.data || response.data;
      state.currentItem.value = itemData as Attribute;
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
      if (!apiModule.index) {
        throw new Error('API module does not have index method');
      }
      const response = await apiModule.index(params);
      const data = response.data;
      state.items.value = ((data as any)?.data || data || []) as Attribute[];
      // Optimized: Mutate existing pagination ref instead of creating new object
      extractPagination(data as any, state.pagination.value);
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
    state.items.value.push(item as Attribute);
  },
  extraActions: {
    fetchByCategory: async (storeState, categoryId: number | string) => {
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

