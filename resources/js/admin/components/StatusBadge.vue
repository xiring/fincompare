<template>
  <span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
    :class="badgeClass"
  >
    {{ label }}
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    status?: string | boolean | null;
  }>(),
  {
    status: '',
  }
);

const normalized = computed(() => {
  if (typeof props.status === 'boolean') {
    return props.status ? 'active' : 'inactive';
  }
  return (props.status || '').toString().toLowerCase();
});

const label = computed(() => {
  const value = normalized.value;
  if (value === 'active') return 'Active';
  if (value === 'inactive') return 'Inactive';
  if (value === 'draft') return 'Draft';
  if (value === 'published') return 'Published';
  if (value === 'archived') return 'Archived';
  return value || '-';
});

const badgeClass = computed(() => {
  switch (normalized.value) {
    case 'active':
    case 'published':
      return 'bg-green-100 text-green-800';
    case 'draft':
      return 'bg-amber-100 text-amber-800';
    case 'archived':
    case 'inactive':
      return 'bg-charcoal-100 text-charcoal-700';
    default:
      return 'bg-charcoal-100 text-charcoal-700';
  }
});
</script>


