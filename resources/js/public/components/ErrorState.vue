<template>
  <div class="bg-white border rounded-2xl p-12 text-center">
    <div class="max-w-md mx-auto">
      <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
        <ErrorIcon class="w-8 h-8 text-red-600" />
      </div>
      <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ title }}</h3>
      <p class="text-sm text-gray-600 mb-6">{{ message || error }}</p>
      <div class="flex gap-3 justify-center">
        <button
          v-if="showRetry"
          @click="$emit('retry')"
          type="button"
          class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white transition-all shadow-sm hover:shadow-md btn-brand-primary"
          style="color: #ffffff !important;"
        >
          <RefreshIcon class="w-5 h-5 mr-2" />
          {{ retryText }}
        </button>
        <slot name="actions">
          <router-link
            v-if="backUrl"
            :to="backUrl"
            class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-gray-700 bg-white border border-gray-300 transition-all shadow-sm hover:shadow-md hover:bg-gray-50"
          >
            {{ backText }}
          </router-link>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ErrorIcon, RefreshIcon } from './icons';

defineProps({
  title: {
    type: String,
    default: 'Something went wrong'
  },
  message: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  showRetry: {
    type: Boolean,
    default: true
  },
  retryText: {
    type: String,
    default: 'Try Again'
  },
  backUrl: {
    type: String,
    default: ''
  },
  backText: {
    type: String,
    default: 'Go Back'
  }
});

defineEmits(['retry']);
</script>

