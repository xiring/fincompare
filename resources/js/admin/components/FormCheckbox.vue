<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <div class="space-y-2">
      <label
        v-for="option in options"
        :key="getOptionValue(option)"
        :class="[
          'flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition-colors',
          'hover:bg-gray-50 dark:hover:bg-gray-700',
          error ? 'border-red-300 dark:border-red-600' : 'border-gray-200 dark:border-gray-600'
        ]"
      >
        <input
          :id="`${id}-${getOptionValue(option)}`"
          :type="multiple ? 'checkbox' : 'radio'"
          :value="getOptionValue(option)"
          :checked="isChecked(option)"
          :disabled="disabled"
          class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
          @change="handleChange(option, $event)"
        />
        <span class="text-sm text-gray-700 dark:text-gray-300">{{ getOptionLabel(option) }}</span>
      </label>
      <p v-if="options.length === 0" class="text-sm text-gray-500 dark:text-gray-400">No options available</p>
    </div>
    <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ hint }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  id: {
    type: String,
    required: true
  },
  modelValue: {
    type: [String, Number, Array],
    default: () => []
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
  multiple: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
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

const emit = defineEmits(['update:modelValue']);

const getOptionValue = (option) => {
  return typeof option === 'object' ? option[props.optionValue] : option;
};

const getOptionLabel = (option) => {
  return typeof option === 'object' ? option[props.optionLabel] : option;
};

const isChecked = (option) => {
  const value = getOptionValue(option);
  if (props.multiple) {
    return Array.isArray(props.modelValue) && props.modelValue.includes(value);
  }
  return props.modelValue === value;
};

const handleChange = (option, event) => {
  const value = getOptionValue(option);
  if (props.multiple) {
    const currentValues = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
    if (event.target.checked) {
      currentValues.push(value);
    } else {
      const index = currentValues.indexOf(value);
      if (index > -1) {
        currentValues.splice(index, 1);
      }
    }
    emit('update:modelValue', currentValues);
  } else {
    emit('update:modelValue', event.target.checked ? value : '');
  }
};
</script>

