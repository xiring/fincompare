<template>
  <div class="bg-white border rounded-2xl p-12 text-center">
    <component
      :is="icon"
      :class="iconClass"
      class="mx-auto h-12 w-12 text-gray-400"
    />
    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ title }}</h3>
    <p class="mt-2 text-sm text-gray-600">{{ message }}</p>
    <div v-if="showAction" class="mt-6">
      <slot name="action">
        <router-link
          v-if="actionUrl"
          :to="actionUrl"
          class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold transition-colors shadow-sm btn-brand-primary"
          style="color: #ffffff !important;"
        >
          {{ actionText }}
        </router-link>
      </slot>
    </div>
  </div>
</template>

<script setup>
import { TEXT } from '../utils';
import { EmptyBoxIcon } from './icons';

defineProps({
  title: {
    type: String,
    required: true
  },
  message: {
    type: String,
    required: true
  },
  icon: {
    type: Object,
    default: () => EmptyBoxIcon
  },
  iconClass: {
    type: String,
    default: ''
  },
  showAction: {
    type: Boolean,
    default: true
  },
  actionUrl: {
    type: String,
    default: ''
  },
  actionText: {
    type: String,
    default: TEXT.GET_STARTED
  }
});
</script>

