<template>
  <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-charcoal-900/50" @click="$emit('update:modelValue', false)"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6 border border-charcoal-200">
      <h3 class="text-lg font-semibold text-charcoal-900">{{ title }}</h3>
      <p class="mt-2 text-sm text-charcoal-700">{{ message }}</p>
      <div class="mt-6 flex justify-end gap-3">
        <button
          type="button"
          class="px-4 py-2 rounded-lg border border-charcoal-300 text-charcoal-700 hover:bg-charcoal-100"
          @click="$emit('update:modelValue', false)"
        >
          Cancel
        </button>
        <button
          type="button"
          class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"
          @click="confirm"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    modelValue: boolean;
    title?: string;
    message?: string;
  }>(),
  {
    title: 'Are you sure?',
    message: 'This action cannot be undone.',
  }
);

const emit = defineEmits<{
  'update:modelValue': [value: boolean];
  confirm: [];
}>();

const confirm = () => {
  emit('confirm');
  emit('update:modelValue', false);
};
</script>


