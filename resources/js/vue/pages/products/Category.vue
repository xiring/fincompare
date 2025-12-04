<template>
  <ProductsIndex :category-slug="$route.params.slug" />
</template>

<script setup>
import ProductsIndex from './Index.vue';
</script>
