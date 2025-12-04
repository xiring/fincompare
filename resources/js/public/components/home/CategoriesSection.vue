<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-12"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">{{ title }}</h2>
        <router-link v-if="showViewAll" :to="viewAllUrl" class="text-sm text-[color:var(--brand-primary)] hover:underline font-medium">
          {{ viewAllText }}
        </router-link>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <router-link
          v-for="(category, index) in categories"
          :key="category.id"
          :to="`/products?category=${category.slug}`"
          :style="{ animationDelay: `${index * 50}ms` }"
          :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
          class="group relative bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-lg transition-all duration-300 flex flex-col items-center text-center overflow-visible min-h-[180px]"
        >
          <!-- Offer Banner -->
          <div v-if="index < 2 && showOffers" class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10">
            <div class="relative bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-lg shadow-sm whitespace-nowrap">
              {{ index === 1 ? '5% Cashback' : 'Cashback Offer' }}
              <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full">
                <div class="w-0 h-0 border-l-[6px] border-r-[6px] border-t-[6px] border-l-transparent border-r-transparent border-t-green-100"></div>
              </div>
            </div>
          </div>
          <!-- Category Icon/Image -->
          <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 border border-[color:var(--brand-primary)]/10 shadow-sm overflow-hidden flex-shrink-0">
            <img
              v-if="category.image_url"
              :src="getImageUrl(category.image_url)"
              :alt="category.name || 'Category'"
              loading="lazy"
              class="w-full h-full object-cover"
            />
            <DollarIcon class="w-14 h-14 text-[color:var(--brand-primary)]" />
          </div>
          <!-- Category Name -->
          <h3 class="text-sm font-semibold text-gray-900 group-hover:text-[color:var(--brand-primary)] transition-colors mt-auto leading-tight">
            {{ category.name || 'Category' }}
          </h3>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { getImageUrl } from '../../utils';
import { DollarIcon } from '../icons';

defineProps({
  categories: {
    type: Array,
    required: true
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Explore Financial Products'
  },
  showViewAll: {
    type: Boolean,
    default: true
  },
  viewAllUrl: {
    type: String,
    default: '/products'
  },
  viewAllText: {
    type: String,
    default: 'Browse all'
  },
  showOffers: {
    type: Boolean,
    default: true
  }
});

const sectionRef = ref(null);
</script>

