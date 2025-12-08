/**
 * LocalStorage composable with reactive state and type safety
 */

import { ref, watch, type Ref } from 'vue';
import { safeJsonParse } from '../utils/helpers';

interface UseLocalStorageOptions<T> {
  serializer?: (value: T) => string;
  deserializer?: (value: string, fallback: T) => T;
}

interface UseLocalStorageReturn<T> {
  value: Ref<T>;
  remove: () => void;
  clear: () => void;
}

export function useLocalStorage<T = any>(
  key: string,
  defaultValue: T | null = null,
  options: UseLocalStorageOptions<T> = {}
): UseLocalStorageReturn<T> {
  const { serializer = JSON.stringify, deserializer = safeJsonParse } = options;

  // Initialize from localStorage or use default
  const storedValue = localStorage.getItem(key);
  const initialValue = storedValue ? deserializer(storedValue, defaultValue as T) : (defaultValue as T);

  const state = ref<T>(initialValue);

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
  const remove = (): void => {
    state.value = defaultValue as T;
    localStorage.removeItem(key);
  };

  /**
   * Clear all localStorage (use with caution)
   */
  const clear = (): void => {
    localStorage.clear();
    state.value = defaultValue as T;
  };

  return {
    value: state as Ref<T>,
    remove,
    clear,
  };
}

