/**
 * LocalStorage composable with reactive state and type safety
 */

import { ref, watch } from 'vue';
import { safeJsonParse } from '../utils/helpers';

/**
 * @template T
 * @param {string} key
 * @param {T} defaultValue
 * @param {object} options
 * @returns {Object}
 */
export function useLocalStorage(key, defaultValue = null, options = {}) {
  const { serializer = JSON.stringify, deserializer = safeJsonParse } = options;

  // Initialize from localStorage or use default
  const storedValue = localStorage.getItem(key);
  const initialValue = storedValue ? deserializer(storedValue, defaultValue) : defaultValue;

  const state = ref(initialValue);

  // Watch for changes and sync to localStorage
  watch(
    state,
    (newValue) => {
      if (newValue === null || newValue === undefined) {
        localStorage.removeItem(key);
      } else {
        try {
          localStorage.setItem(key, serializer(newValue));
        } catch (err) {
          console.error(`Failed to save to localStorage key "${key}":`, err);
        }
      }
    },
    { deep: true }
  );

  /**
   * Remove item from localStorage
   */
  const remove = () => {
    state.value = defaultValue;
    localStorage.removeItem(key);
  };

  /**
   * Clear all localStorage (use with caution)
   */
  const clear = () => {
    localStorage.clear();
    state.value = defaultValue;
  };

  return {
    value: state,
    remove,
    clear,
  };
}

