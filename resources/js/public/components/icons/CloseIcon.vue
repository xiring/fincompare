<template>
  <svg
    stroke="currentColor"
    fill="none"
    viewBox="0 0 24 24"
    :class="className"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M6 18L18 6M6 6l12 12"
    />
  </svg>
</template>

<script setup>
defineProps({
  className: {
    type: String,
    default: 'h-6 w-6'
  }
});
</script>

