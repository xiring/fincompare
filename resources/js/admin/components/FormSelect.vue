<template>
  <div :class="wrapperClass">
    <label v-if="label" :for="id" :class="['block text-sm font-medium text-charcoal-700', dense ? 'mb-1' : 'mb-2']">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="stringValue"
      :required="required"
      :disabled="disabled"
      :class="selectClass"
      @change="handleChange"
    >
      <option v-if="placeholder !== false && !stringValue" value="">{{ typeof placeholder === 'string' ? placeholder : '-- Select --' }}</option>
      <option
        v-for="option in options"
        :key="getOptionValue(option)"
        :value="String(getOptionValue(option))"
      >
        {{ getOptionLabel(option) }}
      </option>
    </select>
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  id: string;
  modelValue?: string | number | null;
  label?: string;
  options: Array<Record<string, any> | string | number>;
  optionValue?: string;
  optionLabel?: string;
  required?: boolean;
  placeholder?: string | boolean;
  error?: string;
  hint?: string;
  disabled?: boolean;
  dense?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  label: '',
  optionValue: 'id',
  optionLabel: 'name',
  required: false,
  placeholder: '-- Select --',
  error: '',
  hint: '',
  disabled: false,
  dense: false,
});

const emit = defineEmits<{
  'update:modelValue': [value: string | number | null];
}>();

const getOptionValue = (option: Record<string, any> | string | number): string | number => {
  return typeof option === 'object' ? option[props.optionValue] : option;
};

const getOptionLabel = (option: Record<string, any> | string | number): string => {
  return typeof option === 'object' ? option[props.optionLabel] : String(option);
};

// Convert modelValue to string for proper comparison with option values
const stringValue = computed(() => {
  if (props.modelValue === null || props.modelValue === undefined) {
    return '';
  }
  return String(props.modelValue);
});

const handleChange = (event: Event): void => {
  const target = event.target as HTMLSelectElement;
  const value = target.value;
  // For required fields, don't emit null - keep as empty string for validation
  // For optional fields, emit null to indicate no selection
  if (value === '') {
    emit('update:modelValue', props.required ? '' : null);
  } else {
    // Try to convert to number if it's a numeric string, otherwise keep as string
    const numValue = Number(value);
    emit('update:modelValue', isNaN(numValue) ? value : numValue);
  }
};

const wrapperClass = computed(() => (props.dense ? 'mb-0 inline-block' : 'mb-6'));

const selectClass = computed(() => {
  const base = [
    'border',
    'rounded-lg',
    'focus:ring-2',
    'focus:ring-primary-500',
    'focus:border-primary-500',
    'bg-white',
    'text-charcoal-900',
    'transition-colors',
  ];
  if (props.dense) {
    base.push('px-3', 'py-2', 'min-w-[160px]');
  } else {
    base.push('block', 'w-full', 'px-4', 'py-2.5');
  }
  base.push(props.error ? 'border-red-300' : 'border-charcoal-300');
  if (props.disabled) base.push('opacity-50', 'cursor-not-allowed');
  return base.join(' ');
});
</script>

