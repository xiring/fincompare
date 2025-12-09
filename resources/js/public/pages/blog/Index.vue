<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">{{ TEXT.BLOG }}</h1>
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
                <SearchIcon />
              </span>
          <input
            v-model="filters.q"
            @input="debouncedSearch"
            :placeholder="TEXT.LABEL_SEARCH_POSTS"
            :disabled="loading"
            class="w-full pl-10 pr-3 py-2.5 rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ TEXT.LABEL_CATEGORY }}</label>
          <select
            v-model="filters.category"
            @change="applyFilters"
            :disabled="loading"
            class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <option value="">{{ TEXT.LABEL_ALL }}</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ TEXT.TAG }}</label>
          <select
            v-model="filters.tag"
            @change="applyFilters"
            :disabled="loading"
            class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <option value="">{{ TEXT.LABEL_ALL }}</option>
            <option v-for="tag in tags" :key="tag" :value="tag">{{ tag }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ TEXT.SORT }}</label>
          <select
            v-model="filters.sort"
            @change="applyFilters"
            :disabled="loading"
            class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <option value="desc">{{ TEXT.NEWEST }}</option>
            <option value="asc">{{ TEXT.OLDEST }}</option>
          </select>
        </div>
      </form>

      <!-- Posts Grid -->
      <!-- Error State -->
      <ErrorState
        v-if="error && posts.length === 0 && !loading"
        :title="ERROR_MESSAGES.POSTS.LOAD"
        :message="error"
        @retry="fetchPosts"
      />

      <!-- Loading skeleton when applying filters/search -->
      <div v-else-if="loading && posts.length === 0" id="posts-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <BlogPostSkeleton v-for="i in 6" :key="i" />
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
            loading="lazy"
            class="w-full h-40 object-cover rounded-lg mb-3"
          />
          <div class="text-xs text-gray-500">{{ post.category || TEXT.GENERAL }}</div>
          <h2 class="mt-1 font-semibold text-gray-900">{{ post.title }}</h2>
          <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ getExcerpt(post.content) }}</p>
          <router-link
            :to="`/blog/${post.slug}`"
            class="mt-3 inline-flex text-sm text-[color:var(--brand-primary)] hover:underline"
          >
            {{ TEXT.READ_MORE }}
          </router-link>
        </article>
      </div>

      <EmptyState
        v-else-if="!loading"
        :title="EMPTY_STATES.NO_POSTS.TITLE"
        :message="EMPTY_STATES.NO_POSTS.MESSAGE"
        :icon="DocumentIcon"
        :show-action="false"
      />

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
        <span v-show="loading" class="mt-3">{{ TEXT.LABEL_LOADING }}</span>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { apiService, default as apiClient } from '../../services/api';
import { debounce, getExcerpt, TEXT, ERROR_MESSAGES, EMPTY_STATES } from '../../utils';
import { useSEO, useErrorHandling } from '../../composables';
import { SearchIcon, DocumentIcon } from '../../components/icons';
import { ErrorState, BlogPostSkeleton, EmptyState } from '../../components';
import GuestLayout from '../../layouts/GuestLayout.vue';
import type { BlogPost } from '../../types/index';

const route = useRoute();
const router = useRouter();

const posts = ref<BlogPost[]>([]);
const categories = ref<string[]>([]);
const tags = ref<string[]>([]);
const loading = ref<boolean>(false);
const { error, handleError, clearError } = useErrorHandling();
const hasNext = ref<boolean>(false);
const nextPageUrl = ref<string | null>(null);
const showProgressBar = ref<boolean>(false);
const progress = ref<number>(0);
const sentinelRef = ref<HTMLElement | null>(null);

interface Filters {
  q: string;
  category: string;
  tag: string;
  sort: 'asc' | 'desc';
}

const filters = ref<Filters>({
  q: (route.query.q as string) || '',
  category: (route.query.category as string) || '',
  tag: (route.query.tag as string) || '',
  sort: (route.query.sort as 'asc' | 'desc') || 'desc',
});

useSEO({
  title: 'Blog',
  description: 'Read our latest articles about financial products, tips, and insights to help you make informed financial decisions.',
  keywords: ['financial blog', 'financial tips', 'money advice', 'financial education'],
});

const getQueryParams = (): Record<string, string> => {
  const params: Record<string, string> = {};
  if (filters.value.q) params.q = filters.value.q;
  if (filters.value.category) params.category = filters.value.category;
  if (filters.value.tag) params.tag = filters.value.tag;
  if (filters.value.sort) params.sort = filters.value.sort;
  return params;
};

const fetchPosts = async (url: string | null = null): Promise<void> => {
  if (loading.value) return;

  loading.value = true;
  showProgressBar.value = true;
  progress.value = 10;

  try {
    let response: any;
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
      posts.value.push(...(response.data.posts.data || []));
    } else {
      // Replace posts for new search/filter
      posts.value = response.data.posts.data || [];
      categories.value = response.data.categories || [];
      tags.value = response.data.tags || [];
    }

    nextPageUrl.value = response.data.posts.next_page_url;
    hasNext.value = !!nextPageUrl.value;
    progress.value = 100;
    clearError();
  } catch (err: any) {
    handleError(err, ERROR_MESSAGES.POSTS.LOAD_DETAIL);
    // Only set error if we don't have any posts yet
    if (posts.value.length === 0) {
      posts.value = [];
    }
  } finally {
    loading.value = false;
    setTimeout(() => {
      showProgressBar.value = false;
      progress.value = 0;
    }, 300);
  }
};

const applyFilters = (): void => {
  // Clear posts immediately to show loading state
  posts.value = [];

  // Update URL without reload
  const query: Record<string, string> = {};
  if (filters.value.q) query.q = filters.value.q;
  if (filters.value.category) query.category = filters.value.category;
  if (filters.value.tag) query.tag = filters.value.tag;
  if (filters.value.sort !== 'desc') query.sort = filters.value.sort;

  router.replace({ query });
  fetchPosts();
};

const loadMore = (): void => {
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
      entries.forEach((entry) => {
        if (entry.isIntersecting && hasNext.value && !loading.value) {
          loadMore();
        }
      });
    }, { threshold: 0.1 });

    observer.observe(sentinelRef.value as unknown as Element);
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
