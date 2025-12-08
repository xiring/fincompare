/**
 * Composable for consistent error handling across pages
 */

import { ref, type Ref } from 'vue';

export function useErrorHandling() {
  const error = ref<string | null>(null);

  /**
   * Set error message
   */
  const setError = (err: Error | string | any): void => {
    if (err?.response?.data?.message) {
      error.value = err.response.data.message;
    } else if (err?.message) {
      error.value = err.message;
    } else if (typeof err === 'string') {
      error.value = err;
    } else {
      error.value = 'An unexpected error occurred. Please try again.';
    }
  };

  /**
   * Clear error
   */
  const clearError = (): void => {
    error.value = null;
  };

  /**
   * Handle error with optional default message
   */
  const handleError = (err: Error | string | any, defaultMessage: string = 'Something went wrong. Please try again.'): void => {
    if (err?.response?.data?.message) {
      error.value = err.response.data.message;
    } else if (err?.message) {
      error.value = err.message;
    } else if (typeof err === 'string') {
      error.value = err;
    } else {
      error.value = defaultMessage;
    }
    console.error('Error:', err);
  };

  return {
    error,
    setError,
    clearError,
    handleError,
  };
}

