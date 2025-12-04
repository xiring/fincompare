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
      d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"
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

