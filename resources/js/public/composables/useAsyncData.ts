/**
 * Generic async data composable with loading, error, and retry logic
 */

import { ref, computed, type Ref, type ComputedRef } from 'vue';

interface UseAsyncDataOptions {
  immediate?: boolean;
  retryCount?: number;
  retryDelay?: number;
}

interface UseAsyncDataReturn<T> {
  data: Ref<T | null>;
  loading: ComputedRef<boolean>;
  error: ComputedRef<any>;
  hasData: ComputedRef<boolean>;
  hasError: ComputedRef<boolean>;
  execute: (force?: boolean) => Promise<T | null>;
  refresh: () => Promise<T | null>;
  reset: () => void;
  retryAttempt: ComputedRef<number>;
}

export function useAsyncData<T = any>(
  fetcher: (() => Promise<T>) | Promise<T>,
  options: UseAsyncDataOptions = {}
): UseAsyncDataReturn<T> {
  const { immediate = true, retryCount = 0, retryDelay = 1000 } = options;

  const data = ref<T | null>(null);
  const loading = ref<boolean>(false);
  const error = ref<any>(null);
  const retryAttempt = ref<number>(0);

  /**
   * Execute the fetcher function
   */
  const execute = async (force: boolean = false): Promise<T | null> => {
    // Don't execute if already loading (unless forced)
    if (loading.value && !force) {
      return data.value;
    }

    // Return cached data if exists and not forcing (unless it's the first call)
    if (data.value && !force && retryAttempt.value === 0) {
      return data.value;
    }

    loading.value = true;
    error.value = null;

    try {
      // Always call fetcher to get fresh data (fetcher can be a function that reads current state)
      const result = await (typeof fetcher === 'function' ? fetcher() : fetcher);
      data.value = result;
      retryAttempt.value = 0;
      return result;
    } catch (err) {
      error.value = err;

      // Retry logic
      if (retryAttempt.value < retryCount) {
        retryAttempt.value++;
        await new Promise((resolve) => setTimeout(resolve, retryDelay));
        return execute(force);
      }

      throw err;
    } finally {
      loading.value = false;
    }
  };

  /**
   * Refresh data
   */
  const refresh = (): Promise<T | null> => execute(true);

  /**
   * Reset state
   */
  const reset = (): void => {
    data.value = null;
    error.value = null;
    retryAttempt.value = 0;
  };

  const hasData = computed(() => data.value !== null);
  const hasError = computed(() => error.value !== null);

  // Execute immediately if requested
  if (immediate && typeof fetcher === 'function') {
    execute();
  }

  return {
    data,
    loading: computed(() => loading.value),
    error: computed(() => error.value),
    hasData,
    hasError,
    execute,
    refresh,
    reset,
    retryAttempt: computed(() => retryAttempt.value),
  };
}

