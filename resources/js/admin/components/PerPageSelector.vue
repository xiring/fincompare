<template>
  <div class="flex items-center gap-2">
    <label for="per-page" class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
      Show:
    </label>
    <select
      id="per-page"
      :value="modelValue"
      @change="$emit('update:modelValue', parseInt($event.target.value))"
      class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
    >
      <option :value="5">5</option>
      <option :value="10">10</option>
      <option :value="20">20</option>
      <option :value="50">50</option>
      <option :value="100">100</option>
    </select>
    <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">per page</span>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: Number,
    default: 5
  }
});

defineEmits(['update:modelValue']);
</script>

