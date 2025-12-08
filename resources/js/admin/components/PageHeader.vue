<template>
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-charcoal-800">{{ title }}</h1>
    <p v-if="description" class="mt-1 text-sm text-charcoal-600">{{ description }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  }
});
</script>

