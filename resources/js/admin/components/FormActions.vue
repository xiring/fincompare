<template>
  <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
    <slot name="before" />
    <router-link
      v-if="cancelRoute"
      :to="cancelRoute"
      class="inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
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
          ? 'bg-primary-600 text-white hover:bg-primary-700'
          : 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600'
      ]"
    >
      <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
      <span>{{ loading ? loadingText : submitText }}</span>
    </button>
    <slot name="after" />
  </div>
</template>

<script setup>
import LoadingSpinner from './LoadingSpinner.vue';

defineProps({
  loading: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  submitText: {
    type: String,
    default: 'Save'
  },
  loadingText: {
    type: String,
    default: 'Saving...'
  },
  cancelRoute: {
    type: String,
    default: ''
  },
  showSubmit: {
    type: Boolean,
    default: true
  },
  submitVariant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary'].includes(value)
  }
});
</script>

