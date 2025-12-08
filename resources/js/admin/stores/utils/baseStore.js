/**
 * Base Store Factory
 * Creates a Pinia store with common CRUD operations
 */

import { defineStore } from 'pinia';
import { ref } from 'vue';
import { extractPagination } from './pagination';

/**
 * Creates a base store with common CRUD operations
 * @param {string} storeName - Name of the store
 * @param {Object} apiModule - API module from adminApi (e.g., adminApi.products)
 * @param {Object} options - Additional options
 * @param {Function} options.onCreate - Callback after creating an item
 * @param {Function} options.onUpdate - Callback after updating an item
 * @param {Function} options.onDelete - Callback after deleting an item
 * @param {Function} options.onFetchItem - Custom fetchItem implementation
 * @param {Function} options.onFetchItems - Custom fetchItems implementation
 * @param {Object} options.extraActions - Additional custom actions
 * @param {Object} options.extraState - Additional state properties
 */
export function createBaseStore(storeName, apiModule, options = {}) {
  const {
    onCreate,
    onUpdate,
    onDelete,
    onFetchItem,
    onFetchItems,
    extraActions = {},
    extraState = {},
  } = options;

  return defineStore(storeName, () => {
    // Base state
    const items = ref([]);
    const currentItem = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const pagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
    });

    // Create state getter function to avoid object creation overhead
    const getState = () => ({
      items,
      currentItem,
      loading,
      error,
      pagination,
      ...extraState,
    });

    // Fetch items (list)
    const fetchItems = async (params = {}) => {
      if (onFetchItems) {
        return onFetchItems({ state: getState(), apiModule, params, extractPagination });
      }

      loading.value = true;
      error.value = null;
      try {
        const response = await apiModule.index(params);
        const data = response.data;
        items.value = data.data || data || [];
        // Optimized: Mutate existing pagination ref instead of creating new object
        extractPagination(data, pagination.value);
        return items.value;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Fetch single item
    const fetchItem = async (id) => {
      if (onFetchItem) {
        return onFetchItem({ state: getState(), apiModule, id });
      }

      loading.value = true;
      error.value = null;
      try {
        const response = await apiModule.show ? await apiModule.show(id) : await apiModule.edit(id);
        const itemData = response.data?.data || response.data;
        currentItem.value = itemData;
        return currentItem.value;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Create item
    const createItem = async (data) => {
      loading.value = true;
      error.value = null;
      try {
        const response = await apiModule.create(data);
        const itemData = response.data?.data || response.data;

        if (onCreate) {
          onCreate({ state: getState(), item: itemData });
        } else {
          // Default: add to items array if it's a list resource
          if (items.value && Array.isArray(items.value)) {
            items.value.push(itemData);
          }
        }

        return itemData;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Update item
    const updateItem = async (id, data) => {
      loading.value = true;
      error.value = null;
      try {
        const response = await apiModule.update(id, data);
        const itemData = response.data?.data || response.data;

        if (onUpdate) {
          onUpdate({ state: getState(), id, item: itemData });
        } else {
          // Default: update in items array and currentItem
          // Use Map for O(1) lookup if items array is large (future optimization)
          const index = items.value.findIndex(item => item.id === id);
          if (index !== -1) {
            items.value[index] = itemData;
          }
          if (currentItem.value?.id === id) {
            currentItem.value = itemData;
          }
        }

        return itemData;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Delete item
    const deleteItem = async (id) => {
      loading.value = true;
      error.value = null;
      try {
        await apiModule.delete(id);

        if (onDelete) {
          onDelete({ state: getState(), id });
        } else {
          // Default: remove from items array and clear currentItem if needed
          // More efficient: find index first, then splice (O(n) but only one pass)
          const index = items.value.findIndex(item => item.id === id);
          if (index !== -1) {
            items.value.splice(index, 1);
          }
          if (currentItem.value?.id === id) {
            currentItem.value = null;
          }
        }
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Clear current item
    const clearCurrentItem = () => {
      currentItem.value = null;
    };

    // Build extra actions with access to store state
    const builtExtraActions = {};
    for (const [key, action] of Object.entries(extraActions)) {
      if (typeof action === 'function') {
        builtExtraActions[key] = (...args) => {
          return action(getState(), ...args);
        };
      } else {
        builtExtraActions[key] = action;
      }
    }

    return {
      items,
      currentItem,
      loading,
      error,
      pagination,
      ...Object.keys(extraState).reduce((acc, key) => {
        acc[key] = extraState[key];
        return acc;
      }, {}),
      fetchItems,
      fetchItem,
      createItem,
      updateItem,
      deleteItem,
      clearCurrentItem,
      ...builtExtraActions,
    };
  });
}

