<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="currentColor"
    :class="className"
  >
    <path d="M2 5.75C2 4.784 2.784 4 3.75 4h2.086c.74 0 1.393.477 1.61 1.184l.741 2.407a1.75 1.75 0 0 1-.47 1.76l-1.016 1.016a14.753 14.753 0 0 0 6.772 6.772l1.016-1.016c.47-.47 1.17-.653 1.76-.47l2.407.742A1.75 1.75 0 0 1 20 18.164V20.25A1.75 1.75 0 0 1 18.25 22h-.5C9.268 22 2 14.732 2 6.25v-.5z"/>
  </svg>
</template>

<script setup>
defineProps({
  className: {
    type: String,
    default: 'h-4 w-4'
  }
});
</script>

