<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :class="[
        'block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors',
        error ? 'border-red-300 dark:border-red-600' : 'border-gray-300 dark:border-gray-600',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ]"
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option v-if="placeholder" value="">{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="getOptionValue(option)"
        :value="getOptionValue(option)"
      >
        {{ getOptionLabel(option) }}
      </option>
    </select>
    <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ hint }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  id: {
    type: String,
    required: true
  },
  modelValue: {
    type: [String, Number],
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  options: {
    type: Array,
    required: true
  },
  optionValue: {
    type: String,
    default: 'id'
  },
  optionLabel: {
    type: String,
    default: 'name'
  },
  required: {
    type: Boolean,
    default: false
  },
  placeholder: {
    type: String,
    default: '-- Select --'
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
  }
});

defineEmits(['update:modelValue']);

const getOptionValue = (option) => {
  return typeof option === 'object' ? option[props.optionValue] : option;
};

const getOptionLabel = (option) => {
  return typeof option === 'object' ? option[props.optionLabel] : option;
};
</script>

