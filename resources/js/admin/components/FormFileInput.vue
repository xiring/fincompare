<template>
  <div class="mb-6">
    <label v-if="label" :for="id" class="block text-sm font-medium text-charcoal-700 mb-2">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      type="file"
      :accept="accept"
      :required="required"
      :disabled="disabled"
      :class="[
        'block w-full text-sm text-charcoal-500',
        'file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0',
        'file:text-sm file:font-medium',
        'file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100',
        'transition-colors',
        error ? 'border-red-300' : '',
        disabled ? 'opacity-50 cursor-not-allowed' : ''
      ]"
      @change="handleFileChange"
    />
    <p v-if="hint && !error" class="mt-1.5 text-xs text-charcoal-500">{{ hint }}</p>
    <p v-if="error" class="mt-1.5 text-sm text-red-600">{{ error }}</p>
    <div v-if="preview && previewUrl" class="mt-3">
      <img
        v-if="previewType === 'image'"
        :src="previewUrl"
        :alt="preview"
        class="h-32 w-32 object-cover rounded-lg border border-charcoal-200"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  id: {
    type: String,
    required: true
  },
  modelValue: {
    type: [File, null],
    default: null
  },
  label: {
    type: String,
    default: ''
  },
  accept: {
    type: String,
    default: '*/*'
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
  },
  preview: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']);

const previewUrl = ref(null);
const previewType = ref(null);

const handleFileChange = (event) => {
  const file = event.target.files[0] || null;
  emit('update:modelValue', file);

  if (file && props.preview) {
    if (file.type.startsWith('image/')) {
      previewType.value = 'image';
      const reader = new FileReader();
      reader.onload = (e) => {
        previewUrl.value = e.target.result;
      };
      reader.readAsDataURL(file);
    } else {
      previewType.value = null;
      previewUrl.value = null;
    }
  } else {
    previewUrl.value = null;
    previewType.value = null;
  }
};

watch(() => props.modelValue, (newValue) => {
  if (!newValue) {
    previewUrl.value = null;
    previewType.value = null;
  }
});
</script>

