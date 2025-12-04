<template>
  <router-link 
    :to="to"
    class="h-full inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition-colors"
    :class="[
      active 
        ? 'border-white text-white' 
        : 'border-transparent text-[var(--brand-text)] hover:text-black hover:border-white/40'
    ]"
  >
    <slot />
  </router-link>
</template>

<script setup>
defineProps({
  to: {
    type: String,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  }
});
</script>
