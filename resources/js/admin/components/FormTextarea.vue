<template>
  <div class="mb-6">
    <label v-if="label" :for="id" class="block text-sm font-medium text-charcoal-700 mb-2">
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
        'block w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors resize-y',
        error ? 'border-red-300' : 'border-charcoal-300',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
      @blur="$emit('blur', $event)"
    ></textarea>
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  id: string;
  modelValue?: string;
  label?: string;
  required?: boolean;
  placeholder?: string;
  error?: string;
  hint?: string;
  disabled?: boolean;
  rows?: number;
}

withDefaults(defineProps<Props>(), {
  modelValue: '',
  label: '',
  required: false,
  placeholder: '',
  error: '',
  hint: '',
  disabled: false,
  rows: 4,
});

defineEmits<{
  'update:modelValue': [value: string];
  blur: [event: FocusEvent];
}>();
</script>

