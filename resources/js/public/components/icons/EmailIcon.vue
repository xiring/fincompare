<template>
  <svg
    viewBox="0 0 24 24"
    fill="currentColor"
    :class="className"
  >
    <path d="M2.01 4.5c-.56 0-1.01.45-1.01 1v13c0 .55.45 1 1.01 1h19.98c.56 0 1.01-.45 1.01-1v-13c0-.55-.45-1-1.01-1h-19.98zm17.99 2-7.99 5.01-7.99-5.01h15.98zm1 11.5h-17.98v-9.72l8.99 5.64 8.99-5.64v9.72z"/>
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

