<template>
  <div class="space-y-6">
    <h3 v-if="title" class="text-lg font-semibold text-gray-900 dark:text-white">{{ title }}</h3>
    <p v-if="description" class="text-sm text-gray-600 dark:text-gray-400 -mt-4">{{ description }}</p>
    <slot />
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: ''
  },
  description: {
    type: String,
    default: ''
  }
});
</script>

