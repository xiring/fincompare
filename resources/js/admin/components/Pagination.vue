<template>
  <div v-if="pagination && pagination.total > pagination.per_page" class="bg-white rounded-lg shadow-sm border border-charcoal-200 px-6 py-4">
    <div class="flex items-center justify-between">
      <div class="text-sm text-charcoal-700">
        Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
      </div>
      <div class="flex gap-2">
        <button
          v-if="pagination.prev_page_url"
          @click="$emit('page-change', pagination.current_page - 1)"
          class="px-4 py-2 border border-charcoal-300 rounded-lg text-sm font-medium text-charcoal-700 hover:bg-gray-50 transition-colors"
        >
          Previous
        </button>
        <button
          v-if="pagination.next_page_url"
          @click="$emit('page-change', pagination.current_page + 1)"
          class="px-4 py-2 border border-charcoal-300 rounded-lg text-sm font-medium text-charcoal-700 hover:bg-gray-50 transition-colors"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PaginationMeta } from '../../types/index';

interface Props {
  pagination: PaginationMeta | null;
}

defineProps<Props>();

defineEmits<{
  'page-change': [page: number];
}>();
</script>

