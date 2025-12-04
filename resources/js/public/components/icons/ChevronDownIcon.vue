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
      d="M19 9l-7 7-7-7"
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

