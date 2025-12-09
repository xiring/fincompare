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

<script setup lang="ts">
import { computed } from 'vue';
import LoadingSpinner from './LoadingSpinner.vue';

type ButtonVariant = 'primary' | 'secondary' | 'danger' | 'success';
type ButtonType = 'button' | 'submit' | 'reset';

interface Props {
  type?: ButtonType;
  variant?: ButtonVariant;
  disabled?: boolean;
  loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'button',
  variant: 'primary',
  disabled: false,
  loading: false,
});

defineEmits<{
  click: [event: MouseEvent];
}>();

const variantClasses = computed(() => {
  const variants: Record<ButtonVariant, string> = {
    primary: 'bg-primary-500 text-white hover:bg-primary-600',
    secondary: 'bg-white',
    danger: 'bg-red-600 text-white hover:bg-red-700',
    success: 'bg-green-600 text-white hover:bg-green-700',
  };
  return variants[props.variant] || variants.primary;
});
</script>

