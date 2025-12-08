<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-sm transition-colors',
      'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500',
      'disabled:opacity-50 disabled:cursor-not-allowed',
      variantClasses
    ]"
    @click="$emit('click', $event)"
  >
    <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue';
import LoadingSpinner from './LoadingSpinner.vue';

const props = defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'success'].includes(value)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  }
});

defineEmits(['click']);

const variantClasses = computed(() => {
  const variants = {
    primary: 'bg-primary-600 text-white hover:bg-primary-700',
    secondary: 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600',
    danger: 'bg-red-600 text-white hover:bg-red-700',
    success: 'bg-green-600 text-white hover:bg-green-700'
  };
  return variants[props.variant] || variants.primary;
});
</script>

