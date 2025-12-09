<template>
  <div>
    <!-- Provider dropdown - check this FIRST before data_type -->
    <select
      v-if="isProvider"
      :value="stringValue"
      @change="handlePartnerChange"
      :required="attr.is_required"
      class="block w-full px-4 py-2.5 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors"
    >
      <option value="">-- Select Partner --</option>
      <option v-for="partner in partners" :key="partner.id" :value="String(partner.id)">
        {{ partner.name }}
      </option>
    </select>
    <!-- Text input -->
    <input
      v-else-if="attr.data_type === 'text'"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      type="text"
      :required="attr.is_required"
      class="block w-full px-4 py-2.5 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors"
    />
    <!-- Number input -->
    <input
      v-else-if="attr.data_type === 'number' || attr.data_type === 'percentage'"
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      type="number"
      step="any"
      :required="attr.is_required"
      class="block w-full px-4 py-2.5 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors"
    />
    <!-- Boolean checkbox -->
    <div v-else-if="attr.data_type === 'boolean'">
      <label class="flex items-center gap-3">
        <input
          :checked="modelValue === '1' || modelValue === true || modelValue === 1"
          @change="$emit('update:modelValue', ($event.target as HTMLInputElement).checked ? '1' : '0')"
          type="checkbox"
          class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
        />
        <span class="text-sm text-charcoal-700">Yes</span>
      </label>
    </div>
    <!-- JSON textarea -->
    <textarea
      v-else
      :value="jsonValue"
      @input="handleJsonInput"
      rows="3"
      :required="attr.is_required"
      class="block w-full px-4 py-2.5 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors resize-y"
      placeholder='{"key": "value"}'
    ></textarea>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Attribute, Partner } from '../types/index';

interface Props {
  attr: Attribute;
  modelValue?: string | number | boolean | Record<string, any>;
  partners?: Partner[];
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  partners: () => [],
});

const emit = defineEmits<{
  'update:modelValue': [value: string | number | boolean | Record<string, any>];
}>();

const isProvider = computed(() => {
  return props.attr.slug?.toLowerCase() === 'provider' || props.attr.name?.toLowerCase() === 'provider';
});

const stringValue = computed(() => {
  if (props.modelValue === null || props.modelValue === undefined || props.modelValue === '') {
    return '';
  }
  return String(props.modelValue);
});

const handlePartnerChange = (event: Event): void => {
  const target = event.target as HTMLSelectElement;
  const value = target.value;
  if (value === '') {
    emit('update:modelValue', '');
  } else {
    // Convert to number for partner ID
    const numValue = Number(value);
    emit('update:modelValue', isNaN(numValue) ? value : numValue);
  }
};

const jsonValue = computed(() => {
  if (props.attr.data_type === 'json') {
    if (typeof props.modelValue === 'object' && props.modelValue !== null) {
      return JSON.stringify(props.modelValue, null, 2);
    }
    return String(props.modelValue || '');
  }
  return String(props.modelValue || '');
});

const handleJsonInput = (event: Event): void => {
  const target = event.target as HTMLTextAreaElement;
  const value = target.value;
  if (props.attr.data_type === 'json') {
    try {
      const parsed = JSON.parse(value);
      emit('update:modelValue', parsed);
    } catch {
      emit('update:modelValue', value);
    }
  } else {
    emit('update:modelValue', value);
  }
};
</script>

