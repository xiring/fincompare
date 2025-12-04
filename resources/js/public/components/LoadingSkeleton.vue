<template>
  <div :class="containerClass">
    <div v-for="i in count" :key="i" :class="itemClass" class="animate-pulse">
      <slot :index="i">
        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
      </slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  count: {
    type: Number,
    default: 3
  },
  containerClass: {
    type: String,
    default: 'space-y-4'
  },
  itemClass: {
    type: String,
    default: ''
  }
});
</script>

