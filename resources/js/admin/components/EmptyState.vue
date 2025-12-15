<template>
  <div class="flex flex-col items-center justify-center py-12 text-center text-charcoal-600">
    <div class="h-12 w-12 rounded-full bg-charcoal-100 flex items-center justify-center mb-4">
      <slot name="icon">
        <svg class="h-6 w-6 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-9 4h12M5 9h14M5 5h14M5 17h14" />
        </svg>
      </slot>
    </div>
    <h3 class="text-lg font-semibold text-charcoal-900 mb-2">{{ title }}</h3>
    <p class="text-sm text-charcoal-600 max-w-md mb-4">
      <slot>{{ description }}</slot>
    </p>
    <slot name="action"></slot>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    title?: string;
    description?: string;
  }>(),
  {
    title: 'Nothing here yet',
    description: 'No data to display.',
  }
);
</script>


