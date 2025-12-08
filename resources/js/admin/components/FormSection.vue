<template>
  <div class="space-y-6">
    <h3 v-if="title" class="text-lg font-semibold text-charcoal-800">{{ title }}</h3>
    <p v-if="description" class="text-sm text-charcoal-600">{{ description }}</p>
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

