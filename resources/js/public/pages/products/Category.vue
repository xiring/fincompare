<template>
  <ProductsIndex :category-slug="$route.params.slug" />
</template>

<script setup lang="ts">
import ProductsIndex from './Index.vue';
</script>
