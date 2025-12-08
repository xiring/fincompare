<template>
  <div>
    <input
      v-if="attr.data_type === 'string'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      type="text"
      :required="attr.is_required"
      class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
    />
    <input
      v-else-if="attr.data_type === 'number' || attr.data_type === 'percentage'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      type="number"
      step="any"
      :required="attr.is_required"
      class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
    />
    <div v-else-if="attr.data_type === 'boolean'" class="mt-2">
      <label class="flex items-center gap-3">
        <input
          :checked="modelValue === '1' || modelValue === true || modelValue === 1"
          @change="$emit('update:modelValue', $event.target.checked ? '1' : '0')"
          type="checkbox"
          class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
        />
        <span class="text-sm text-charcoal-700">Yes</span>
      </label>
    </div>
    <select
      v-else-if="isProvider"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :required="attr.is_required"
      class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
    >
      <option value="">-- Select Partner --</option>
      <option v-for="partner in partners" :key="partner.id" :value="partner.id">
        {{ partner.name }}
      </option>
    </select>
    <textarea
      v-else
      :value="jsonValue"
      @input="handleJsonInput"
      rows="3"
      :required="attr.is_required"
      class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
      placeholder='{"key": "value"}'
    ></textarea>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  attr: {
    type: Object,
    required: true
  },
  modelValue: {
    type: [String, Number, Boolean, Object],
    default: ''
  },
  partners: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:modelValue']);

const isProvider = computed(() => {
  return props.attr.slug?.toLowerCase() === 'provider' || props.attr.name?.toLowerCase() === 'provider';
});

const jsonValue = computed(() => {
  if (props.attr.data_type === 'json') {
    if (typeof props.modelValue === 'object') {
      return JSON.stringify(props.modelValue, null, 2);
    }
    return props.modelValue || '';
  }
  return props.modelValue;
});

const handleJsonInput = (event) => {
  const value = event.target.value;
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

