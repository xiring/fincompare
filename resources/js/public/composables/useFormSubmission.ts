/**
 * Composable for form submission with consistent error handling and success feedback
 */

import { ref, nextTick } from 'vue';

interface UseFormSubmissionOptions {
  onSuccess?: () => void;
  onError?: (error: any) => void;
  successMessage?: string;
  scrollToSuccess?: boolean;
  successSelector?: string;
}

interface FormErrors {
  [key: string]: string[] | undefined;
  _general?: string[];
}

export function useFormSubmission(
  submitFn: (data: Record<string, any>) => Promise<any>,
  options: UseFormSubmissionOptions = {}
) {
  const {
    onSuccess = null,
    onError = null,
    scrollToSuccess = true,
    successSelector = '[data-success-message]',
  } = options;

  const loading = ref<boolean>(false);
  const errors = ref<FormErrors>({});
  const success = ref<boolean>(false);

  /**
   * Submit form
   */
  const submit = async (data: Record<string, any>): Promise<void> => {
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
    } catch (err: any) {
      if (err.response?.data?.errors) {
        errors.value = err.response.data.errors;
      } else {
        errors.value = {
          _general: [err.response?.data?.message || err.message || 'Failed to submit. Please try again.'],
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
  const reset = (): void => {
    loading.value = false;
    errors.value = {};
    success.value = false;
  };

  return {
    loading,
    errors,
    success,
    submit,
    reset,
  };
}

