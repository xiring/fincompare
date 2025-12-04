/**
 * Generic async data composable with loading, error, and retry logic
 * @template T
 */

import { ref, computed } from 'vue';

/**
 * @typedef {Object} UseAsyncDataOptions
 * @property {boolean} immediate - Execute immediately on mount
 * @property {number} retryCount - Number of retries on failure
 * @property {number} retryDelay - Delay between retries in ms
 */

/**
 * @template T
 * @param {() => Promise<T>} fetcher
 * @param {UseAsyncDataOptions} options
 * @returns {Object}
 */
export function useAsyncData(fetcher, options = {}) {
  const { immediate = true, retryCount = 0, retryDelay = 1000 } = options;

  const data = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const retryAttempt = ref(0);

  /**
   * Execute the fetcher function
   * @param {boolean} force - Force refresh even if data exists
   */
  const execute = async (force = false) => {
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
        await new Promise(resolve => setTimeout(resolve, retryDelay));
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
  const refresh = () => execute(true);

  /**
   * Reset state
   */
  const reset = () => {
    data.value = null;
    error.value = null;
    retryAttempt.value = 0;
  };

  const hasData = computed(() => data.value !== null);
  const hasError = computed(() => error.value !== null);

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

