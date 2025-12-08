<template>
  <div v-if="pagination && pagination.total > pagination.per_page" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4">
    <div class="flex items-center justify-between">
      <div class="text-sm text-gray-700 dark:text-gray-400">
        Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
      </div>
      <div class="flex gap-2">
        <button
          v-if="pagination.prev_page_url"
          @click="$emit('page-change', pagination.current_page - 1)"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
          Previous
        </button>
        <button
          v-if="pagination.next_page_url"
          @click="$emit('page-change', pagination.current_page + 1)"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  pagination: {
    type: Object,
    default: null
  }
});

defineEmits(['page-change']);
</script>

