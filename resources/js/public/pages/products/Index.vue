<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-3xl">
          <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">{{ categoryName }}</h1>
          <p class="mt-4 text-lg text-white/90 leading-relaxed">Browse, filter, and compare financial products side-by-side to find the best options for your needs.</p>
        </div>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Search and Category Pills -->
      <div class="mb-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 shadow-sm">
          <form @submit.prevent="applyFilters" class="mb-4">
            <div class="flex-1 relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <SearchIcon />
              </span>
              <input
                v-model="filters.q"
                @input="debouncedSearch"
                :placeholder="TEXT.LABEL_SEARCH_PRODUCTS"
                :disabled="loading"
                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)]/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm"
              />
            </div>
          </form>
          <div class="flex flex-wrap gap-2">
            <router-link
              v-for="cat in categories.slice(0, 8)"
              :key="cat.id"
              :to="{ path: '/products', query: { category: cat.slug } }"
              :class="isActiveCategory(cat.slug)
                ? 'bg-[color:var(--brand-primary)] text-white border-[color:var(--brand-primary)] shadow-sm'
                : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100 hover:border-gray-300'"
              class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full text-xs border font-medium transition-all duration-200"
            >
              <img v-if="cat.image_url" :src="getImageUrl(cat.image_url)" :alt="cat.name" class="w-4 h-4 rounded-full object-cover flex-shrink-0">
              <span>{{ cat.name }}</span>
            </router-link>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-1/4 w-full">
          <div class="lg:sticky lg:top-8 p-5 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between mb-5">
              <h2 class="text-lg font-semibold text-gray-900">{{ TEXT.LABEL_FILTER_BY }}</h2>
              <button
                v-if="filters.q || filters.category || filters.partner_id || filters.featured"
                @click="resetFilters"
                type="button"
                class="text-xs text-[color:var(--brand-primary)] hover:underline font-medium"
              >
                {{ TEXT.LABEL_RESET }}
              </button>
            </div>
            <form @submit.prevent="applyFilters" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">{{ TEXT.LABEL_CATEGORY }}</label>
                <select
                  v-model="filters.category"
                  @change="applyFilters"
                  class="w-full px-3 py-2.5 rounded-lg border-gray-300 text-sm focus:border-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)]/20 transition-colors bg-white"
                >
                  <option value="">{{ TEXT.LABEL_ALL }}</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">{{ TEXT.LABEL_PARTNER }}</label>
                <select
                  v-model="filters.partner_id"
                  @change="applyFilters"
                  :disabled="loading"
                  class="w-full px-3 py-2.5 rounded-lg border-gray-300 text-sm focus:border-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)]/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed bg-white"
                >
                  <option value="">{{ TEXT.LABEL_ALL }}</option>
                  <option v-for="partner in partners" :key="partner.id" :value="partner.id">{{ partner.name }}</option>
                </select>
              </div>
              <div class="pt-4 mt-4 border-t border-gray-200">
                <label class="inline-flex items-center gap-3 text-sm font-semibold text-gray-900 cursor-pointer group">
                  <input
                    type="checkbox"
                    v-model="filters.featured"
                    @change="applyFilters"
                    :disabled="loading"
                    class="w-4 h-4 rounded border-gray-300 text-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)]/20 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                  <span class="group-hover:text-[color:var(--brand-primary)] transition-colors">{{ TEXT.LABEL_FEATURED_ONLY }}</span>
                </label>
              </div>
            </form>
          </div>
        </aside>

        <!-- Products -->
        <main class="lg:w-3/4 w-full">
          <div v-show="showProgressBar" class="fixed top-0 left-0 right-0 z-30">
            <div class="h-0.5 bg-[color:var(--brand-primary)] transition-all" :style="`width: ${progress}%`"></div>
          </div>

          <!-- Results Header -->
          <div v-if="!loading && products.length > 0" class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-2">
              <h2 class="text-sm font-semibold text-gray-700">
                {{ TEXT.SHOWING_RESULTS }}
                <span class="text-[color:var(--brand-primary)] font-bold">{{ products.length }}</span>
                <span v-if="totalProducts > products.length" class="text-gray-600">of {{ totalProducts }}</span>
                <span class="text-gray-600">{{ products.length === 1 ? TEXT.RESULT_FOUND : TEXT.RESULTS_FOUND }}</span>
              </h2>
            </div>
            <div class="flex items-center gap-3">
              <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">{{ TEXT.SORT_BY }}:</label>
              <select
                v-model="sortBy"
                @change="applySorting"
                class="px-4 py-2 pr-10 rounded-lg border-gray-300 text-sm font-medium focus:border-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)]/20 bg-white shadow-sm transition-all appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-no-repeat bg-right-2 bg-[length:1.25rem]"
              >
                <option value="newest">{{ TEXT.SORT_NEWEST }}</option>
                <option value="oldest">{{ TEXT.SORT_OLDEST }}</option>
                <option value="name_asc">{{ TEXT.SORT_NAME_ASC }}</option>
                <option value="name_desc">{{ TEXT.SORT_NAME_DESC }}</option>
              </select>
            </div>
          </div>

          <!-- Error State -->
          <ErrorState
            v-if="error && products.length === 0 && !loading"
            :title="ERROR_MESSAGES.PRODUCTS.LOAD"
            :message="error"
            @retry="fetchProducts"
          />

          <!-- Loading skeleton when applying filters/search -->
          <div v-else-if="loading && products.length === 0" id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <ProductSkeleton v-for="i in 6" :key="i" />
          </div>

          <div v-else-if="products.length > 0" id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <ProductCard
              v-for="product in products"
              :key="product.id"
              :product="product"
            />
          </div>
          <EmptyState
            v-else-if="!loading"
            :title="EMPTY_STATES.NO_PRODUCTS.TITLE"
            :message="EMPTY_STATES.NO_PRODUCTS.MESSAGE"
            action-url="/products"
            :action-text="TEXT.BROWSE_ALL_PRODUCTS"
          />

          <div
            id="infinite-sentinel"
            ref="sentinelRef"
            class="mt-8 flex flex-col items-center justify-center text-sm text-gray-500"
            v-show="hasNext"
          >
            <div v-show="loading" class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="i in 3" :key="i" class="p-4 bg-white border rounded-2xl">
                <div class="h-5 w-40 bg-gray-200 rounded animate-pulse"></div>
                <div class="mt-4 h-24 bg-gray-100 rounded animate-pulse"></div>
              </div>
            </div>
            <span v-show="loading" class="mt-3">{{ TEXT.LABEL_LOADING }}</span>
          </div>
        </main>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { apiService, default as apiClient } from '../../services/api';
import { debounce, getImageUrl, TEXT, ERROR_MESSAGES, EMPTY_STATES } from '../../utils';
import { useSEO, useErrorHandling } from '../../composables';
import { SearchIcon } from '../../components/icons';
import { ErrorState, EmptyState, ProductSkeleton } from '../../components';
import GuestLayout from '../../layouts/GuestLayout.vue';
import ProductCard from '../../components/ProductCard.vue';

const route = useRoute();
const router = useRouter();

const products = ref([]);
const categories = ref([]);
const partners = ref([]);
const loading = ref(false);
const { error, handleError, clearError } = useErrorHandling();
const hasNext = ref(false);
const nextPageUrl = ref(null);
const showProgressBar = ref(false);
const progress = ref(0);
const sentinelRef = ref(null);
const categoryName = ref(TEXT.LABEL_ALL_PRODUCTS);
const totalProducts = ref(0);
const sortBy = ref('newest');

const filters = ref({
  q: route.query.q || '',
  category: route.query.category || '',
  partner_id: route.query.partner_id || '',
  featured: route.query.featured === '1' || route.query.featured === 'true'
});

useSEO({
  title: TEXT.PRODUCTS,
  description: 'Browse and compare financial products including loans, credit cards, and more. Filter by category, partner, and features to find the best options.',
  keywords: TEXT.SEO_KEYWORDS_FINANCIAL_PRODUCTS
});

const isActiveCategory = (slug) => {
  return filters.value.category === slug;
};

const getQueryParams = () => {
  const params = {};
  if (filters.value.q) params.q = filters.value.q;
  if (filters.value.category) params.category = filters.value.category;
  if (filters.value.partner_id) params.partner_id = filters.value.partner_id;
  if (filters.value.featured) params.featured = '1';
  return params;
};

const fetchProducts = async (url = null) => {
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
      response = await apiService.getProducts(params);
    }

    progress.value = 60;

    if (url) {
      products.value.push(...response.data.products.data);
    } else {
      products.value = response.data.products.data || [];
      categories.value = response.data.categories || [];
      partners.value = response.data.partners || [];
      totalProducts.value = response.data.products.total || products.value.length;
      if (response.data.category) {
        categoryName.value = response.data.category.name || TEXT.LABEL_ALL_PRODUCTS;
      }
    }

    nextPageUrl.value = response.data.products.next_page_url;
    hasNext.value = !!nextPageUrl.value;
    progress.value = 100;
    clearError();
  } catch (err) {
    handleError(err, ERROR_MESSAGES.PRODUCTS.LOAD_DETAIL);
    // Only set error if we don't have any products yet
    if (products.value.length === 0) {
      products.value = [];
    }
  } finally {
    loading.value = false;
    setTimeout(() => {
      showProgressBar.value = false;
      progress.value = 0;
    }, 300);
  }
};

const applyFilters = () => {
  // Clear products immediately to show loading state
  products.value = [];

  const query = {};
  if (filters.value.q) query.q = filters.value.q;
  if (filters.value.category) query.category = filters.value.category;
  if (filters.value.partner_id) query.partner_id = filters.value.partner_id;
  if (filters.value.featured) query.featured = '1';

  router.replace({ query });
  fetchProducts();
};

const resetFilters = () => {
  // Clear products immediately to show loading state
  products.value = [];

  filters.value = {
    q: '',
    category: '',
    partner_id: '',
    featured: false
  };
  sortBy.value = 'newest';
  router.replace({ query: {} });
  fetchProducts();
};

const applySorting = () => {
  // Sort products client-side for now
  const sorted = [...products.value];

  switch (sortBy.value) {
    case 'name_asc':
      sorted.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
      break;
    case 'name_desc':
      sorted.sort((a, b) => (b.name || '').localeCompare(a.name || ''));
      break;
    case 'oldest':
      sorted.sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
      break;
    case 'newest':
    default:
      sorted.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
      break;
  }

  products.value = sorted;
};

// Debounced search to avoid too many API calls while typing
const debouncedSearch = debounce(() => {
  applyFilters();
}, 500);

const loadMore = () => {
  if (nextPageUrl.value && !loading.value) {
    fetchProducts(nextPageUrl.value);
  }
};

// Watch for route query changes (e.g., when redirected from /categories/{slug})
watch(() => route.query, (newQuery) => {
  // Clear products immediately to show loading state
  products.value = [];

  filters.value = {
    q: newQuery.q || '',
    category: newQuery.category || '',
    partner_id: newQuery.partner_id || '',
    featured: newQuery.featured === '1' || newQuery.featured === 'true'
  };
  fetchProducts();
}, { immediate: false });

onMounted(() => {
  // Initialize filters from route query (handles redirects from /categories/{slug})
  if (route.query.category && !filters.value.category) {
    filters.value.category = route.query.category;
  }

  fetchProducts();

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
.category-pill-active {
  @apply bg-[color:var(--brand-primary)] text-white border-[color:var(--brand-primary)] shadow-sm;
}
</style>
