<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">Blog</h1>
        <p class="mt-2 text-white/90">Insights and guides from FinCompare.</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Progress bar -->
      <div v-show="showProgressBar" class="fixed top-0 left-0 right-0 z-30">
        <div class="h-0.5 bg-[color:var(--brand-primary)] transition-all" :style="`width: ${progress}%`"></div>
      </div>

      <!-- Filters -->
      <form @submit.prevent="applyFilters" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div class="relative md:col-span-2">
          <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
          </span>
          <input
            v-model="filters.q"
            @input="debouncedSearch"
            placeholder="Search posts"
            :disabled="loading"
            class="w-full pl-10 pr-3 py-2.5 rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select
            v-model="filters.category"
            @change="applyFilters"
            :disabled="loading"
            class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <option value="">All</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
          <select
            v-model="filters.tag"
            @change="applyFilters"
            :disabled="loading"
            class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <option value="">All</option>
            <option v-for="tag in tags" :key="tag" :value="tag">{{ tag }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sort</label>
          <select
            v-model="filters.sort"
            @change="applyFilters"
            :disabled="loading"
            class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <option value="desc">Newest</option>
            <option value="asc">Oldest</option>
          </select>
        </div>
      </form>

      <!-- Posts Grid -->
      <!-- Loading skeleton when applying filters/search -->
      <div v-if="loading && posts.length === 0" id="posts-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="i in 6" :key="i" class="bg-white border rounded-2xl p-5 animate-pulse">
          <div class="h-40 bg-gray-200 rounded-lg mb-3"></div>
          <div class="h-4 w-24 bg-gray-200 rounded mb-2"></div>
          <div class="h-5 w-3/4 bg-gray-200 rounded mb-2"></div>
          <div class="h-4 w-full bg-gray-100 rounded mb-1"></div>
          <div class="h-4 w-5/6 bg-gray-100 rounded"></div>
        </div>
      </div>

      <div v-else-if="posts.length > 0" id="posts-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <article
          v-for="post in posts"
          :key="post.id"
          class="bg-white border rounded-2xl p-5 flex flex-col"
        >
          <img
            v-if="post.featured_image"
            :src="post.featured_image"
            :alt="post.title"
            class="w-full h-40 object-cover rounded-lg mb-3"
          />
          <div class="text-xs text-gray-500">{{ post.category || 'General' }}</div>
          <h2 class="mt-1 font-semibold text-gray-900">{{ post.title }}</h2>
          <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ getExcerpt(post.content) }}</p>
          <router-link
            :to="`/blog/${post.slug}`"
            class="mt-3 inline-flex text-sm text-[color:var(--brand-primary)] hover:underline"
          >
            Read more
          </router-link>
        </article>
      </div>

      <div v-else-if="!loading" class="bg-white border rounded-2xl p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-gray-900">No posts found</h3>
        <p class="mt-2 text-sm text-gray-600">Try adjusting your filters or search terms.</p>
      </div>

      <!-- Infinite Scroll Sentinel -->
      <div
        id="blog-sentinel"
        ref="sentinelRef"
        class="mt-8 flex flex-col items-center justify-center text-sm text-gray-500"
        v-show="hasNext"
      >
        <div v-show="loading" class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="i in 3" :key="i" class="bg-white border rounded-2xl p-5">
            <div class="h-40 bg-gray-100 rounded animate-pulse"></div>
            <div class="mt-3 h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
            <div class="mt-2 h-5 w-3/4 bg-gray-200 rounded animate-pulse"></div>
            <div class="mt-1 h-4 w-full bg-gray-100 rounded animate-pulse"></div>
          </div>
        </div>
        <span v-show="loading" class="mt-3">Loading…</span>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { apiService, default as apiClient } from '../../services/api';
import { debounce, getExcerpt } from '../../utils';
import { useSEO } from '../../composables';
import GuestLayout from '../../layouts/GuestLayout.vue';

const route = useRoute();
const router = useRouter();

const posts = ref([]);
const categories = ref([]);
const tags = ref([]);
const loading = ref(false);
const hasNext = ref(false);
const nextPageUrl = ref(null);
const showProgressBar = ref(false);
const progress = ref(0);
const sentinelRef = ref(null);

const filters = ref({
  q: route.query.q || '',
  category: route.query.category || '',
  tag: route.query.tag || '',
  sort: route.query.sort || 'desc'
});

useSEO({
  title: 'Blog',
  description: 'Read our latest articles about financial products, tips, and insights to help you make informed financial decisions.',
  keywords: ['financial blog', 'financial tips', 'money advice', 'financial education']
});

const getQueryParams = () => {
  const params = {};
  if (filters.value.q) params.q = filters.value.q;
  if (filters.value.category) params.category = filters.value.category;
  if (filters.value.tag) params.tag = filters.value.tag;
  if (filters.value.sort) params.sort = filters.value.sort;
  return params;
};

const fetchPosts = async (url = null) => {
  if (loading.value) return;

  loading.value = true;
  showProgressBar.value = true;
  progress.value = 10;

  try {
    let response;
    if (url) {
      // For pagination, use the full URL (Laravel pagination URLs are absolute)
      response = await apiClient.get(url);
    } else {
      // For initial load or filters, use query params
      const params = getQueryParams();
      response = await apiService.getBlogPosts(params);
    }

    progress.value = 60;

    if (url) {
      // Append to existing posts for infinite scroll
      posts.value.push(...response.data.posts.data);
    } else {
      // Replace posts for new search/filter
      posts.value = response.data.posts.data || [];
      categories.value = response.data.categories || [];
      tags.value = response.data.tags || [];
    }

    nextPageUrl.value = response.data.posts.next_page_url;
    hasNext.value = !!nextPageUrl.value;
    progress.value = 100;
  } catch (err) {
    console.error('Failed to fetch blog posts:', err);
    // Could set error state here for better UX
  } finally {
    loading.value = false;
    setTimeout(() => {
      showProgressBar.value = false;
      progress.value = 0;
    }, 300);
  }
};

const applyFilters = () => {
  // Clear posts immediately to show loading state
  posts.value = [];

  // Update URL without reload
  const query = {};
  if (filters.value.q) query.q = filters.value.q;
  if (filters.value.category) query.category = filters.value.category;
  if (filters.value.tag) query.tag = filters.value.tag;
  if (filters.value.sort !== 'desc') query.sort = filters.value.sort;

  router.replace({ query });
  fetchPosts();
};

const loadMore = () => {
  if (nextPageUrl.value && !loading.value) {
    fetchPosts(nextPageUrl.value);
  }
};

// Debounced search to avoid too many API calls while typing
const debouncedSearch = debounce(() => {
  applyFilters();
}, 500);

onMounted(() => {
  fetchPosts();

  // Set up intersection observer for infinite scroll
  if (sentinelRef.value) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && hasNext.value && !loading.value) {
          loadMore();
        }
      });
    }, { threshold: 0.1 });

    observer.observe(sentinelRef.value);
  }
});
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
