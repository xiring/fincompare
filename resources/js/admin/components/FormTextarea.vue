<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <textarea
      :id="id"
      :value="modelValue"
      :required="required"
      :placeholder="placeholder"
      :disabled="disabled"
      :rows="rows"
      :class="[
        'block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors resize-y',
        error ? 'border-red-300 dark:border-red-600' : 'border-gray-300 dark:border-gray-600',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ]"
      @input="$emit('update:modelValue', $event.target.value)"
      @blur="$emit('blur', $event)"
    ></textarea>
    <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    required: true
  },
  modelValue: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: false
  },
  placeholder: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  hint: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  rows: {
    type: Number,
    default: 4
  }
});

defineEmits(['update:modelValue', 'blur']);
</script>

