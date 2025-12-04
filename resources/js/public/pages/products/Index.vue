<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">{{ categoryName }}</h1>
        <p class="mt-2 text-white/90">Browse, filter, and compare side-by-side.</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Search and Category Pills -->
      <div class="mb-6">
        <div class="bg-white border rounded-2xl p-4 md:p-5">
          <form @submit.prevent="applyFilters" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <SearchIcon />
              </span>
              <input
                v-model="filters.q"
                @input="debouncedSearch"
                placeholder="Search products..."
                :disabled="loading"
                class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-300 focus-brand disabled:opacity-50 disabled:cursor-not-allowed"
              />
            </div>
          </form>
          <div class="mt-4 flex flex-wrap gap-2">
            <router-link
              v-for="cat in categories.slice(0, 8)"
              :key="cat.id"
              :to="{ path: '/products', query: { category: cat.slug } }"
              :class="isActiveCategory(cat.slug) ? 'category-pill-active' : 'bg-gray-50 text-gray-700 border-gray-300 hover:bg-gray-100'"
              class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border font-medium transition-colors"
            >
              <img v-if="cat.image_url" :src="getImageUrl(cat.image_url)" :alt="cat.name" class="w-4 h-4 rounded-full object-cover">
              <span>{{ cat.name }}</span>
            </router-link>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-1/4 w-full">
          <div class="lg:sticky lg:top-8 p-4 bg-white border rounded-2xl">
            <h2 class="text-lg font-semibold mb-4">Filter by</h2>
            <form @submit.prevent="applyFilters" class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select
                  v-model="filters.category"
                  @change="applyFilters"
                  class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
                >
                  <option value="">All</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Partner</label>
            <select
              v-model="filters.partner_id"
              @change="applyFilters"
              :disabled="loading"
              class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
            >
                  <option value="">All</option>
                  <option v-for="partner in partners" :key="partner.id" :value="partner.id">{{ partner.name }}</option>
                </select>
              </div>
              <div>
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                  <input
                    type="checkbox"
                    v-model="filters.featured"
                    @change="applyFilters"
                    :disabled="loading"
                    class="rounded border-gray-300 text-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)] disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                  <span>Featured Products Only</span>
                </label>
              </div>
              <div class="flex gap-2 mt-4">
                <button
                  type="button"
                  @click="resetFilters"
                  class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                >
                  Reset
                </button>
              </div>
            </form>
          </div>
        </aside>

        <!-- Products -->
        <main class="lg:w-3/4 w-full">
          <div v-show="showProgressBar" class="fixed top-0 left-0 right-0 z-30">
            <div class="h-0.5 bg-[color:var(--brand-primary)] transition-all" :style="`width: ${progress}%`"></div>
          </div>

          <!-- Error State -->
          <ErrorState
            v-if="error && products.length === 0 && !loading"
            title="Failed to load products"
            :message="error"
            @retry="fetchProducts"
          />

          <!-- Loading skeleton when applying filters/search -->
          <div v-else-if="loading && products.length === 0" id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <ProductSkeleton v-for="i in 6" :key="i" />
          </div>

          <div v-else-if="products.length > 0" id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <ProductCard
              v-for="product in products"
              :key="product.id"
              :product="product"
            />
          </div>
          <EmptyState
            v-else-if="!loading"
            title="No products found"
            message="Try adjusting your filters or search terms."
            action-url="/products"
            action-text="Browse All Products"
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
            <span v-show="loading" class="mt-3">Loading…</span>
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
import { debounce, getImageUrl } from '../../utils';
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
const categoryName = ref('All Products');

const filters = ref({
  q: route.query.q || '',
  category: route.query.category || '',
  partner_id: route.query.partner_id || '',
  featured: route.query.featured === '1' || route.query.featured === 'true'
});

useSEO({
  title: 'Products',
  description: 'Browse and compare financial products including loans, credit cards, and more. Filter by category, partner, and features to find the best options.',
  keywords: ['financial products', 'compare products', 'loans', 'credit cards', 'financial comparison']
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
      if (response.data.category) {
        categoryName.value = response.data.category.name || 'All Products';
      }
    }

    nextPageUrl.value = response.data.products.next_page_url;
    hasNext.value = !!nextPageUrl.value;
    progress.value = 100;
    clearError();
  } catch (err) {
    handleError(err, 'Failed to load products. Please try again.');
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
  router.replace({ query: {} });
  fetchProducts();
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
