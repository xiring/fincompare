<template>
  <div :class="wrapperClass">
    <label v-if="label" :for="id" :class="['block text-sm font-medium text-charcoal-700', dense ? 'mb-1' : 'mb-2']">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :required="required"
      :placeholder="placeholder"
      :disabled="disabled"
      :class="[
        'border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors',
        dense ? 'px-3 py-2 min-w-[200px]' : 'block w-full px-4 py-2.5',
        error ? 'border-red-300' : 'border-charcoal-300',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      @blur="$emit('blur', $event)"
    />
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
interface Props {
  id: string;
  modelValue?: string | number;
  label?: string;
  type?: string;
  required?: boolean;
  placeholder?: string;
  error?: string;
  hint?: string;
  disabled?: boolean;
  dense?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  label: '',
  type: 'text',
  required: false,
  placeholder: '',
  error: '',
  hint: '',
  disabled: false,
  dense: false,
});

defineEmits<{
  'update:modelValue': [value: string | number];
  blur: [event: FocusEvent];
}>();

const wrapperClass = computed(() => (props.dense ? 'mb-0 inline-block' : 'mb-6'));
</script>

