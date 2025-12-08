<template>
  <div class="mb-6 pt-6 border-t border-charcoal-200">
    <h3 v-if="title" class="text-lg font-semibold text-charcoal-800 mb-2">{{ title }}</h3>
    <p v-if="description" class="text-sm text-charcoal-600 mb-4">{{ description }}</p>
    <div class="space-y-6">
      <slot />
    </div>
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

