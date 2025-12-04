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
      d="M13 7l5 5m0 0l-5 5m5-5H6"
    />
  </svg>
</template>

<script setup>
defineProps({
  className: {
    type: String,
    default: 'h-5 w-5'
  }
});
</script>

