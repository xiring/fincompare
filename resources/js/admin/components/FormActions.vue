<template>
  <div class="flex items-center gap-3 pt-6 mt-6 border-t border-charcoal-200">
    <slot name="before" />
    <router-link
      v-if="cancelRoute"
      :to="cancelRoute"
      class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-charcoal-300 text-charcoal-700 font-medium text-sm hover:bg-charcoal-50 transition-colors"
    >
      Cancel
    </router-link>
    <button
      v-if="showSubmit"
      type="submit"
      :disabled="loading || disabled"
      :class="[
        'inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-sm transition-colors',
        'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500',
        'disabled:opacity-50 disabled:cursor-not-allowed',
        submitVariant === 'primary'
          ? 'bg-primary-500 text-white hover:bg-primary-600'
          : 'bg-white border border-charcoal-300 text-charcoal-700 hover:bg-charcoal-50'
      ]"
    >
      <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
      <span>{{ loading ? loadingText : submitText }}</span>
    </button>
    <slot name="after" />
  </div>
</template>

<script setup lang="ts">
import LoadingSpinner from './LoadingSpinner.vue';

type SubmitVariant = 'primary' | 'secondary';

interface Props {
  loading?: boolean;
  disabled?: boolean;
  submitText?: string;
  loadingText?: string;
  cancelRoute?: string;
  showSubmit?: boolean;
  submitVariant?: SubmitVariant;
}

withDefaults(defineProps<Props>(), {
  loading: false,
  disabled: false,
  submitText: 'Save',
  loadingText: 'Saving...',
  cancelRoute: '',
  showSubmit: true,
  submitVariant: 'primary',
});
</script>

