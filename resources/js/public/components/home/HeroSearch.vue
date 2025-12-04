<template>
  <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <div class="lg:col-span-7 text-center lg:text-left">
          <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
            {{ title }}
          </h1>
          <p class="mt-4 text-white/90 max-w-2xl">
            {{ subtitle }}
          </p>
          <div class="mt-8 bg-white/10 backdrop-blur rounded-2xl p-3 ring-1 ring-white/20">
            <form @submit.prevent="handleSearch" class="flex flex-col sm:flex-row gap-3">
              <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                  <SearchIcon />
                </span>
                <input
                  v-model="searchQuery"
                  :placeholder="searchPlaceholder"
                  class="w-full pl-10 pr-3 py-3 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none"
                />
              </div>
              <div class="flex gap-3">
                <router-link
                  to="/products"
                  class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-[color:var(--brand-primary)] font-semibold shadow hover:bg-gray-50"
                >
                  Browse All
                </router-link>
                <button
                  class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[color:var(--brand-primary)] text-white font-semibold shadow hover:bg-[color:var(--brand-primary-2)]"
                  type="submit"
                >
                  Search
                </button>
              </div>
            </form>
            <div v-if="filterPills && filterPills.length > 0" class="mt-3 flex flex-wrap gap-2 text-xs">
              <button
                v-for="(filter, index) in filterPills"
                :key="index"
                @click="$emit('filter-click', filter.value)"
                class="px-3 py-1 rounded-full bg-white/20 hover:bg-white/30 transition-colors cursor-pointer"
              >
                {{ filter.label }}
              </button>
            </div>
          </div>
        </div>
        <div v-if="showImage" class="lg:col-span-5">
          <div class="relative mx-auto max-w-md">
            <div class="aspect-[4/3] rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center">
              <img :src="imageUrl" :alt="imageAlt" class="rounded-xl shadow-2xl" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { TEXT } from '../../utils';
import { SearchIcon } from '../icons';

const props = defineProps({
  title: {
    type: String,
    default: TEXT.HERO_TITLE
  },
  subtitle: {
    type: String,
    default: TEXT.HERO_SUBTITLE
  },
  searchPlaceholder: {
    type: String,
    default: TEXT.HERO_SEARCH_PLACEHOLDER
  },
  filterPills: {
    type: Array,
    default: () => [
      { label: TEXT.FILTER_0_APR, value: TEXT.FILTER_0_APR },
      { label: TEXT.FILTER_CASHBACK, value: 'cashback' },
      { label: TEXT.FILTER_TRAVEL, value: 'travel' },
      { label: TEXT.FILTER_PERSONAL_LOANS, value: 'personal loan' }
    ]
  },
  showImage: {
    type: Boolean,
    default: true
  },
  imageUrl: {
    type: String,
    default: 'https://placehold.co/640x480?text=Comparison+Preview'
  },
  imageAlt: {
    type: String,
    default: 'Preview'
  }
});

const emit = defineEmits(['filter-click']);

const router = useRouter();
const searchQuery = ref('');

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/products', query: { q: searchQuery.value.trim() } });
  } else {
    router.push({ path: '/products' });
  }
};
</script>

