<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-16 bg-gray-50"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-end justify-between mb-6">
        <h2 class="text-2xl font-bold">{{ title }}</h2>
        <router-link
          v-if="showViewAll"
          :to="viewAllUrl"
          class="text-sm text-[color:var(--brand-primary)] hover:underline"
        >
          {{ viewAllText }}
        </router-link>
      </div>
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <ProductSkeleton v-for="i in 3" :key="i" />
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(product, index) in products"
          :key="product.id"
          :style="{ animationDelay: `${index * 100}ms` }"
          :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
        >
          <ProductCard :product="product" />
        </div>
        <div
          v-if="products.length === 0"
          class="col-span-full text-center py-12 text-gray-500"
        >
          {{ emptyMessage }}
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import ProductCard from '../ProductCard.vue';
import ProductSkeleton from '../ProductSkeleton.vue';

defineProps({
  products: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: "Editor's Picks"
  },
  showViewAll: {
    type: Boolean,
    default: true
  },
  viewAllUrl: {
    type: String,
    default: '/products?featured=1'
  },
  viewAllText: {
    type: String,
    default: 'See all'
  },
  emptyMessage: {
    type: String,
    default: 'No featured products available at the moment.'
  }
});

const sectionRef = ref(null);
</script>

