/**
 * Composable for form submission with consistent error handling and success feedback
 * @param {Function} submitFn - Async function to submit the form
 * @param {Object} options - Configuration options
 * @returns {Object} Form submission utilities
 */
import { ref, nextTick } from 'vue';

export function useFormSubmission(submitFn, options = {}) {
  const {
    onSuccess = null,
    onError = null,
    successMessage = 'Form submitted successfully!',
    scrollToSuccess = true,
    successSelector = '[data-success-message]'
  } = options;

  const loading = ref(false);
  const errors = ref({});
  const success = ref(false);

  /**
   * Submit form
   * @param {Object} data - Form data to submit
   */
  const submit = async (data) => {
    loading.value = true;
    errors.value = {};
    success.value = false;

    try {
      await submitFn(data);
      success.value = true;

      if (onSuccess) {
        onSuccess();
      }

      // Scroll to success message
      if (scrollToSuccess) {
        await nextTick();
        const successElement = document.querySelector(successSelector);
        if (successElement) {
          successElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    } catch (err) {
      if (err.response?.data?.errors) {
        errors.value = err.response.data.errors;
      } else {
        errors.value = {
          _general: [err.response?.data?.message || err.message || 'Failed to submit. Please try again.']
        };
      }

      if (onError) {
        onError(err);
      }
    } finally {
      loading.value = false;
    }
  };

  /**
   * Reset form state
   */
  const reset = () => {
    loading.value = false;
    errors.value = {};
    success.value = false;
  };

  return {
    loading,
    errors,
    success,
    submit,
    reset
  };
}

