<template>
  <div class="mb-6">
    <label v-if="label" :for="id" class="block text-sm font-medium text-charcoal-700 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <div class="space-y-2">
      <label
        v-for="option in options"
        :key="getOptionValue(option)"
        :class="[
          'flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition-colors',
          'hover:bg-charcoal-50',
          error ? 'border-red-300' : 'border-charcoal-200'
        ]"
      >
        <input
          :id="`${id}-${getOptionValue(option)}`"
          :type="multiple ? 'checkbox' : 'radio'"
          :value="getOptionValue(option)"
          :checked="isChecked(option)"
          :disabled="disabled"
          class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
          @change="handleChange(option, $event)"
        />
        <span class="text-sm text-charcoal-700">{{ getOptionLabel(option) }}</span>
      </label>
      <p v-if="options.length === 0" class="text-sm text-charcoal-500">No options available</p>
    </div>
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">

interface Props {
  id: string;
  modelValue?: string | number | Array<string | number>;
  label?: string;
  options: Array<Record<string, any> | string | number>;
  optionValue?: string;
  optionLabel?: string;
  multiple?: boolean;
  required?: boolean;
  error?: string;
  hint?: string;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: () => [],
  label: '',
  optionValue: 'id',
  optionLabel: 'name',
  multiple: false,
  required: false,
  error: '',
  hint: '',
  disabled: false,
});

const emit = defineEmits<{
  'update:modelValue': [value: string | number | Array<string | number>];
}>();

const getOptionValue = (option: Record<string, any> | string | number): string | number => {
  return typeof option === 'object' ? option[props.optionValue] : option;
};

const getOptionLabel = (option: Record<string, any> | string | number): string => {
  return typeof option === 'object' ? option[props.optionLabel] : String(option);
};

const isChecked = (option: Record<string, any> | string | number): boolean => {
  const value = getOptionValue(option);
  if (props.multiple) {
    return Array.isArray(props.modelValue) && props.modelValue.includes(value);
  }
  return props.modelValue === value;
};

const handleChange = (option: Record<string, any> | string | number, event: Event): void => {
  const target = event.target as HTMLInputElement;
  const value = getOptionValue(option);
  if (props.multiple) {
    const currentValues = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
    if (target.checked) {
      currentValues.push(value);
    } else {
      const index = currentValues.indexOf(value);
      if (index > -1) {
        currentValues.splice(index, 1);
      }
    }
    emit('update:modelValue', currentValues);
  } else {
    emit('update:modelValue', target.checked ? value : '');
  }
};
</script>

