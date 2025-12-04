/**
 * Composable for consistent error handling across pages
 * @returns {Object} Error handling utilities
 */
import { ref } from 'vue';

export function useErrorHandling() {
  const error = ref(null);

  /**
   * Set error message
   * @param {Error|string} err - Error object or message string
   */
  const setError = (err) => {
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
  const clearError = () => {
    error.value = null;
  };

  /**
   * Handle error with optional default message
   * @param {Error|string} err - Error object or message string
   * @param {string} defaultMessage - Default message if error parsing fails
   */
  const handleError = (err, defaultMessage = 'Something went wrong. Please try again.') => {
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
    handleError
  };
}

