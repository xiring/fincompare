/**
 * Base Store Factory
 * Creates a Pinia store with common CRUD operations
 */

import { defineStore } from 'pinia';
import { ref, type Ref } from 'vue';
import { extractPagination } from './pagination';
import type { PaginationMeta } from '../../../types/index';
import type { AxiosResponse } from 'axios';

interface ApiModule<T = any> {
  index?: (params?: Record<string, any>) => Promise<AxiosResponse<{ data: T[] } | T[]>>;
  show?: (id: number | string) => Promise<AxiosResponse<{ data: T } | T>>;
  edit?: (id: number | string) => Promise<AxiosResponse<{ data: T } | T>>;
  create?: (data: Partial<T>) => Promise<AxiosResponse<{ data: T } | T>>;
  update?: (id: number | string, data: Partial<T>) => Promise<AxiosResponse<{ data: T } | T>>;
  delete?: (id: number | string) => Promise<AxiosResponse<void>>;
  [key: string]: any;
}

interface StoreState<T = any> {
  items: Ref<T[]>;
  currentItem: Ref<T | null>;
  loading: Ref<boolean>;
  error: Ref<any>;
  pagination: Ref<PaginationMeta>;
  [key: string]: any;
}

interface StoreCallbacks<T = any> {
  onCreate?: (context: { state: StoreState<T>; item: T }) => void;
  onUpdate?: (context: { state: StoreState<T>; id: number | string; item: T }) => void;
  onDelete?: (context: { state: StoreState<T>; id: number | string }) => void;
  onFetchItem?: (context: {
    state: StoreState<T>;
    apiModule: ApiModule<T>;
    id: number | string;
  }) => Promise<T>;
  onFetchItems?: (context: {
    state: StoreState<T>;
    apiModule: ApiModule<T>;
    params: Record<string, any>;
    extractPagination: typeof extractPagination;
  }) => Promise<T[]>;
}

interface BaseStoreOptions<T = any> extends StoreCallbacks<T> {
  extraActions?: Record<string, (...args: any[]) => any>;
  extraState?: Record<string, any>;
}

/**
 * Creates a base store with common CRUD operations
 */
export function createBaseStore<T = any>(
  storeName: string,
  apiModule: ApiModule<T>,
  options: BaseStoreOptions<T> = {}
) {
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
    const items = ref<T[]>([]);
    const currentItem = ref<T | null>(null);
    const loading = ref<boolean>(false);
    const error = ref<any>(null);
    const pagination = ref<PaginationMeta>({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
      prev_page_url: null,
      next_page_url: null,
    });

    // Create state getter function to avoid object creation overhead
    const getState = (): StoreState<T> => ({
      items,
      currentItem,
      loading,
      error,
      pagination,
      ...extraState,
    } as StoreState<T>);

    // Fetch items (list)
    const fetchItems = async (params: Record<string, any> = {}): Promise<T[]> => {
      if (onFetchItems) {
        return onFetchItems({ state: getState(), apiModule, params, extractPagination });
      }

      loading.value = true;
      error.value = null;
      try {
        if (!apiModule.index) {
          throw new Error(`API module ${storeName} does not have an index method`);
        }
        const response = await apiModule.index(params);
        const data = response.data;
        const itemsData = (data as any)?.data || (Array.isArray(data) ? data : []);
        // Type assertion needed due to Vue's UnwrapRefSimple type
        // @ts-ignore - Vue's UnwrapRefSimple type incompatibility with generic T
        items.value = itemsData;
        // Optimized: Mutate existing pagination ref instead of creating new object
        extractPagination(data as any, pagination.value);
        // @ts-ignore - Vue's UnwrapRefSimple type incompatibility with generic T
        return items.value as T[];
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Fetch single item
    const fetchItem = async (id: number | string): Promise<T> => {
      if (onFetchItem) {
        return onFetchItem({ state: getState(), apiModule, id });
      }

      loading.value = true;
      error.value = null;
      try {
        const response = apiModule.show
          ? await apiModule.show(id)
          : apiModule.edit
          ? await apiModule.edit(id)
          : null;

        if (!response) {
          throw new Error(`API module ${storeName} does not have a show or edit method`);
        }

        const itemData = (response.data as any)?.data || response.data;
        currentItem.value = itemData as T;
        return currentItem.value;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Create item
    const createItem = async (data: Partial<T>): Promise<T> => {
      loading.value = true;
      error.value = null;
      try {
        if (!apiModule.create) {
          throw new Error(`API module ${storeName} does not have a create method`);
        }
        const response = await apiModule.create(data);
        const itemData = (response.data as any)?.data || response.data;

        if (onCreate) {
          onCreate({ state: getState(), item: itemData as T });
        } else {
          // Default: add to items array if it's a list resource
          if (items.value && Array.isArray(items.value)) {
            items.value.push(itemData);
          }
        }

        // Type assertion needed due to Vue's UnwrapRefSimple type
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        return itemData as any;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Update item
    const updateItem = async (id: number | string, data: Partial<T>): Promise<T> => {
      loading.value = true;
      error.value = null;
      try {
        if (!apiModule.update) {
          throw new Error(`API module ${storeName} does not have an update method`);
        }
        const response = await apiModule.update(id, data);
        const itemData = (response.data as any)?.data || response.data;

        if (onUpdate) {
          onUpdate({ state: getState(), id, item: itemData as T });
        } else {
          // Default: update in items array and currentItem
          const index = items.value.findIndex((item: any) => item.id === id);
          if (index !== -1) {
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            (items.value as any)[index] = itemData;
          }
          if ((currentItem.value as any)?.id === id) {
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            (currentItem as any).value = itemData;
          }
        }

        // Type assertion needed due to Vue's UnwrapRefSimple type
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        return itemData as any;
      } catch (err) {
        error.value = err;
        throw err;
      } finally {
        loading.value = false;
      }
    };

    // Delete item
    const deleteItem = async (id: number | string): Promise<void> => {
      loading.value = true;
      error.value = null;
      try {
        if (!apiModule.delete) {
          throw new Error(`API module ${storeName} does not have a delete method`);
        }
        await apiModule.delete(id);

        if (onDelete) {
          onDelete({ state: getState(), id });
        } else {
          // Default: remove from items array and clear currentItem if needed
          const index = items.value.findIndex((item: any) => item.id === id);
          if (index !== -1) {
            items.value.splice(index, 1);
          }
          if ((currentItem.value as any)?.id === id) {
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
    const clearCurrentItem = (): void => {
      currentItem.value = null;
    };

    // Clear error
    const clearError = (): void => {
      error.value = null;
    };

    // Build extra actions with access to store state
    const builtExtraActions: Record<string, any> = {};
    for (const [key, action] of Object.entries(extraActions)) {
      if (typeof action === 'function') {
        builtExtraActions[key] = (...args: any[]) => {
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
      }, {} as Record<string, any>),
      fetchItems,
      fetchItem,
      createItem,
      updateItem,
      deleteItem,
      clearCurrentItem,
      clearError,
      ...builtExtraActions,
    };
  });
}

