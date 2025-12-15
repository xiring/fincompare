<template>
  <span
    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full"
    :class="name ? 'bg-primary-50 text-primary-700' : 'bg-charcoal-100 text-charcoal-600'"
  >
    {{ name || '—' }}
  </span>
</template>

<script setup lang="ts">
defineProps<{
  name?: string | null;
}>();
</script>


