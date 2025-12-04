<template>
  <svg
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    viewBox="0 0 24 24"
    :class="className"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M5 10l7-7m0 0l7 7m-7-7v18"
    />
  </svg>
</template>

<script setup>
defineProps({
  className: {
    type: String,
    default: 'w-6 h-6'
  }
});
</script>

