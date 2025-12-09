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

<script setup lang="ts">
withDefaults(defineProps<{
  count?: number;
  containerClass?: string;
  itemClass?: string;
}>(), {
  count: 3,
  containerClass: 'space-y-4',
  itemClass: ''
});
</script>

