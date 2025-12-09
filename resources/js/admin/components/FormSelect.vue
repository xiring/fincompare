<template>
  <div class="mb-6">
    <label v-if="label" :for="id" class="block text-sm font-medium text-charcoal-700 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :class="[
        'block w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors',
        error ? 'border-red-300' : 'border-charcoal-300',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ]"
      @change="handleChange"
    >
      <option v-if="placeholder !== false && !modelValue" value="">{{ typeof placeholder === 'string' ? placeholder : '-- Select --' }}</option>
      <option
        v-for="option in options"
        :key="getOptionValue(option)"
        :value="getOptionValue(option)"
      >
        {{ getOptionLabel(option) }}
      </option>
    </select>
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
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

const handleChange = (event: Event): void => {
  const target = event.target as HTMLSelectElement;
  const value = target.value;
  // Convert empty string to null for optional fields, preserve string values
  if (value === '') {
    emit('update:modelValue', null);
  } else {
    // Try to convert to number if it's a numeric string, otherwise keep as string
    const numValue = Number(value);
    emit('update:modelValue', isNaN(numValue) ? value : numValue);
  }
};
</script>

