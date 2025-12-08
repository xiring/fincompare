<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">{{ TEXT.COMPARE_PRODUCTS_TITLE }}</h1>
        <p class="mt-2 text-white/90">{{ TEXT.COMPARE_PRODUCTS_SUBTITLE }}</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Loading State with Skeleton -->
      <div v-if="loading && products.length === 0" class="space-y-6">
        <!-- Controls Skeleton -->
        <div class="bg-white border rounded-2xl p-4 shadow-sm animate-pulse">
          <div class="flex items-center justify-between">
            <div class="h-6 bg-gray-200 rounded w-48"></div>
            <div class="h-10 bg-gray-200 rounded w-40"></div>
          </div>
        </div>
        <!-- Table Skeleton -->
        <div class="bg-white border rounded-2xl shadow-sm hidden md:block">
          <div class="p-6 border-b">
            <div class="grid grid-cols-3 gap-4">
              <div v-for="i in 3" :key="i" class="space-y-3">
                <div class="h-20 bg-gray-200 rounded-lg"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
              </div>
            </div>
          </div>
          <div class="p-6 space-y-4">
            <div v-for="i in 5" :key="i" class="grid grid-cols-4 gap-4">
              <div class="h-4 bg-gray-200 rounded w-24"></div>
              <div class="h-4 bg-gray-200 rounded"></div>
              <div class="h-4 bg-gray-200 rounded"></div>
              <div class="h-4 bg-gray-200 rounded"></div>
            </div>
          </div>
        </div>
        <!-- Mobile Skeleton -->
        <div class="md:hidden space-y-4">
          <div v-for="i in 2" :key="i" class="bg-white border rounded-2xl p-4 animate-pulse">
            <div class="h-16 bg-gray-200 rounded mb-4"></div>
            <div class="space-y-3">
              <div class="h-4 bg-gray-200 rounded"></div>
              <div class="h-4 bg-gray-200 rounded w-3/4"></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Error State -->
      <div v-else-if="error && !loading" class="bg-white border rounded-2xl p-12 text-center">
        <div class="max-w-md mx-auto">
          <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ ERROR_MESSAGES.COMPARISON.LOAD }}</h3>
          <p class="text-sm text-gray-600 mb-6">{{ ERROR_MESSAGES.COMPARISON.LOAD_DETAIL }}</p>
          <button
            @click="retryLoad"
            type="button"
            class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white transition-all shadow-sm hover:shadow-md btn-brand-primary"
            style="color: #ffffff !important;"
          >
            <RefreshIcon className="w-5 h-5 mr-2.5" />
            {{ TEXT.RETRY }}
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="products.length === 0 && !loading" class="bg-white border rounded-2xl p-12 text-center">
        <EmptyBoxIcon className="mx-auto h-16 w-16 text-gray-300" />
        <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ EMPTY_STATES.NO_COMPARE.TITLE }}</h3>
        <p class="mt-2 text-sm text-gray-600 max-w-md mx-auto">
          {{ EMPTY_STATES.NO_COMPARE.MESSAGE }}
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
          <button
            @click="openAddProductModal"
            type="button"
            class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white transition-all shadow-sm hover:shadow-md btn-brand-primary"
            style="color: #ffffff !important;"
          >
            <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            {{ TEXT.ADD_PRODUCTS }}
          </button>
          <router-link
            to="/products"
            class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors"
          >
            {{ TEXT.BROWSE_ALL_PRODUCTS }}
          </router-link>
        </div>
      </div>

      <div v-else>
        <!-- Controls Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 p-4 bg-white border rounded-2xl shadow-sm">
          <div class="flex flex-wrap items-center gap-3">
            <!-- Product Count Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] text-sm font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              <span>{{ products.length }} product{{ products.length !== 1 ? 's' : '' }}</span>
            </div>
            <div class="h-4 w-px bg-gray-300"></div>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer group relative" title="Highlight features that differ between products">
              <input
                type="checkbox"
                v-model="highlightDiff"
                :aria-label="TEXT.COMPARE_HIGHLIGHT_DIFF"
                class="w-4 h-4 rounded border-gray-300 text-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)] focus:ring-offset-2 cursor-pointer"
              >
              <span class="flex items-center gap-1.5">
                <svg
                  v-if="highlightDiff"
                  class="w-4 h-4 text-[color:var(--brand-primary)] transition-colors"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span>{{ TEXT.COMPARE_HIGHLIGHT_DIFF }}</span>
              </span>
            </label>
            <div class="h-4 w-px bg-gray-300"></div>
            <button
              @click="clearAll"
              type="button"
              :disabled="clearingAll || loading"
              aria-label="Clear all products from compare"
              class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-sm bg-white text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-[color:var(--brand-primary)] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="clearingAll" class="w-4 h-4 mr-2.5 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <span>{{ clearingAll ? TEXT.CLEARING : TEXT.CLEAR_ALL }}</span>
            </button>
          </div>
          <button
            @click="openAddProductModal"
            type="button"
            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-white font-semibold transition-all shadow-sm hover:shadow-md btn-brand-primary"
            style="color: #ffffff !important;"
          >
            <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #ffffff;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span style="color: #ffffff !important;">{{ TEXT.COMPARE_ADD_MORE }}</span>
          </button>
        </div>

        <!-- Desktop Table View -->
        <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm hidden md:block animate-fade-in-up scroll-smooth" style="scrollbar-width: thin;">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-0 bg-gradient-to-r from-gray-50 to-gray-100 z-20 border-r border-gray-200">
                  {{ TEXT.COMPARE_FEATURE }}
                </th>
                <th v-for="p in products" :key="p.id" class="px-6 py-4 text-left min-w-[280px]">
                  <div class="flex flex-col gap-3">
                    <!-- Product Header Card -->
                    <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                      <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <div class="relative flex-shrink-0">
                            <img
                              :src="getProductImage(p)"
                              :alt="p.name || 'Product'"
                              class="w-16 h-16 rounded-lg bg-gray-100 object-cover border border-gray-200"
                            >
                            <img
                              v-if="p.partner?.logo_url"
                              :src="getImageUrl(p.partner.logo_url)"
                              :alt="p.partner?.name || 'Partner'"
                              class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-white border-2 border-white object-contain shadow-sm"
                            >
                          </div>
                          <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm leading-tight mb-1 line-clamp-2">
                              {{ p.name || 'Product' }}
                            </h3>
                            <p v-if="p.partner?.name" class="text-xs text-gray-500 truncate">
                              {{ p.partner.name }}
                            </p>
                          </div>
                        </div>
                        <button
                          @click="removeProduct(p.id)"
                          type="button"
                          :disabled="removingProductId === p.id || clearingAll"
                          :aria-label="`Remove ${p.name || 'product'} from compare`"
                          class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed"
                          :title="TEXT.COMPARE_REMOVE_TITLE"
                        >
                          <svg v-if="removingProductId === p.id" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                          </svg>
                          <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                        </button>
                      </div>
                      <div class="flex gap-2">
                        <router-link
                          :to="`/products/${p.slug || p.id}`"
                          class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                          {{ TEXT.VIEW_DETAILS }}
                        </router-link>
                        <router-link
                          :to="`/lead?product=${p.slug || p.id}`"
                          class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] rounded-lg transition-colors shadow-sm"
                          style="color: #ffffff !important;"
                        >
                          {{ TEXT.APPLY }}
                        </router-link>
                      </div>
                    </div>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="f in features" :key="f.key" class="hover:bg-gray-50/50 transition-colors duration-150">
                <td class="px-6 py-4 text-sm font-semibold text-gray-900 sticky left-0 bg-white z-10 border-r border-gray-200 whitespace-nowrap">
                  {{ f.label }}
                </td>
                <td
                  v-for="p in products"
                  :key="p.id + '-' + f.key"
                  :class="[
                    'px-6 py-4 text-sm align-top transition-colors',
                    getCellClass(p.id, f.key),
                    highlightDiff && hasDifferentValues(f.key) ? 'text-[color:var(--brand-primary)]' : 'text-gray-700'
                  ]"
                >
                  <span :class="highlightDiff && hasDifferentValues(f.key) ? 'font-bold' : 'font-medium'">
                    {{ formatValue(getValue(p.id, f.key)) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Stacked View -->
        <div class="md:hidden space-y-6">
          <div v-for="p in products" :key="p.id" class="bg-white border rounded-2xl shadow-sm overflow-hidden">
            <!-- Product Header -->
            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
              <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                  <div class="relative flex-shrink-0">
                    <img
                      :src="getProductImage(p)"
                      :alt="p.name || 'Product'"
                      class="w-14 h-14 rounded-lg bg-white object-cover border border-gray-200"
                    >
                    <img
                      v-if="p.partner?.logo_url"
                      :src="getImageUrl(p.partner.logo_url)"
                      :alt="p.partner?.name || 'Partner'"
                      class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white border-2 border-white object-contain shadow-sm"
                    >
                  </div>
                  <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 text-sm leading-tight mb-1">
                      {{ p.name || 'Product' }}
                    </h3>
                    <p v-if="p.partner?.name" class="text-xs text-gray-500">
                      {{ p.partner.name }}
                    </p>
                  </div>
                </div>
                <button
                  @click="removeProduct(p.id)"
                  type="button"
                  :disabled="removingProductId === p.id || clearingAll"
                  :aria-label="`Remove ${p.name || 'product'} from compare`"
                  class="flex-shrink-0 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg v-if="removingProductId === p.id" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
              <div class="flex gap-2">
                <router-link
                  :to="`/products/${p.slug || p.id}`"
                  class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-lg transition-colors border border-gray-300"
                >
                  View Details
                </router-link>
                <router-link
                  :to="`/lead?product=${p.slug || p.id}`"
                  class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] rounded-lg transition-colors shadow-sm"
                >
                  Apply
                </router-link>
              </div>
            </div>
            <!-- Features List -->
            <dl class="divide-y divide-gray-200">
              <div
                v-for="f in features"
                :key="'m-'+p.id+'-'+f.key"
                :class="getMobileRowClass(p.id, f.key)"
                class="px-4 py-3 grid grid-cols-2 gap-4"
              >
                <dt class="text-sm font-medium text-gray-600">{{ f.label }}</dt>
                <dd
                  class="text-sm text-right transition-colors"
                  :class="highlightDiff && hasDifferentValues(f.key) ? 'font-bold text-[color:var(--brand-primary)]' : 'font-semibold text-gray-900'"
                >
                  {{ formatValue(getValue(p.id, f.key)) }}
                </dd>
              </div>
            </dl>
          </div>
        </div>

      </div>
    </div>

    <!-- Add Product Modal -->
    <AddProductModal
      :is-open="showAddProductModal"
      @close="closeAddProductModal"
      @added="handleProductsAdded"
    />

    <!-- Confirm Clear All Modal -->
    <ConfirmModal
      :is-open="showClearConfirmModal"
      :title="TEXT.COMPARE_CLEAR_ALL_TITLE"
      :message="TEXT.COMPARE_CLEAR_ALL_MESSAGE"
      description="This action cannot be undone. All products will be removed from your comparison list."
      :confirm-text="TEXT.COMPARE_CLEAR_ALL_CONFIRM"
      :cancel-text="TEXT.CANCEL"
      @confirm="confirmClearAll"
      @close="showClearConfirmModal = false"
    />
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCompare, useCompareData, useSEO } from '../../composables';
import { getImageUrl, TEXT, ERROR_MESSAGES, EMPTY_STATES } from '../../utils';
import { EmptyBoxIcon, RefreshIcon } from '../../components/icons';
import AddProductModal from '../../components/AddProductModal.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import GuestLayout from '../../layouts/GuestLayout.vue';

const route = useRoute();
const router = useRouter();
const highlightDiff = ref<boolean>(true);
const showAddProductModal = ref<boolean>(false);
const showClearConfirmModal = ref<boolean>(false);
const removingProductId = ref<number | null>(null);
const clearingAll = ref<boolean>(false);

const { toggleCompare, clearAll: clearCompareList, compareIds } = useCompare();

// Get product IDs from route or compareIds
const getProductIds = (): number[] => {
  if (route.query.products) {
    return (route.query.products as string)
      .split(',')
      .map((id) => parseInt(id))
      .filter((id) => !isNaN(id));
  }
  return compareIds.value || [];
};

const initialProductIds = getProductIds();
const {
  products,
  features,
  values,
  loading,
  error,
  fetchCompareData,
  updateProductIds,
  getProductImage,
  getValue,
  hasDifferentValues,
} = useCompareData(initialProductIds);

useSEO({
  title: 'Compare Products',
  description: 'Compare multiple financial products side-by-side. View features, rates, and eligibility criteria to make an informed decision.',
  keywords: ['compare products', 'product comparison', 'financial comparison', 'compare loans', 'compare credit cards'],
});

const getCellClass = (productId: number, key: string): string => {
  if (!highlightDiff.value) return '';
  if (hasDifferentValues(key)) {
    return 'bg-gradient-to-r from-[color:var(--brand-primary)]/10 to-[color:var(--brand-primary)]/5 border-l-4 border-[color:var(--brand-primary)] font-semibold';
  }
  return '';
};

const getMobileRowClass = (productId: number, key: string): string => {
  if (!highlightDiff.value) return '';
  if (hasDifferentValues(key)) {
    return 'bg-gradient-to-r from-[color:var(--brand-primary)]/10 to-[color:var(--brand-primary)]/5 border-l-4 border-[color:var(--brand-primary)]';
  }
  return '';
};

/**
 * Format value for display
 */
const formatValue = (value: any): string => {
  if (value === '—' || value === null || value === undefined || value === '') {
    return '—';
  }

  // If it's a number, format it
  if (typeof value === 'number' || !isNaN(parseFloat(String(value)))) {
    const num = parseFloat(String(value));
    // Format large numbers with commas
    if (num >= 1000) {
      return num.toLocaleString('en-US', { maximumFractionDigits: 2 });
    }
    // Format decimals
    if (num % 1 !== 0) {
      return num.toFixed(2);
    }
    return num.toString();
  }

  return String(value);
};

const removeProduct = async (id: number): Promise<void> => {
  removingProductId.value = id;

  try {
    const success = await toggleCompare(id);
    if (success) {
      // Optimistically update UI - remove product immediately
      const currentIds = compareIds.value || [];

      // Update the data with new IDs
      await updateProductIds(currentIds);

      // Update URL to match
      if (currentIds.length > 0) {
        router.replace({ query: { products: currentIds.join(',') } });
      } else {
        router.replace({ query: {} });
      }
    }
  } catch (error: any) {
    console.error('Failed to remove product:', error);
  } finally {
    removingProductId.value = null;
  }
};

const clearAll = (): void => {
  showClearConfirmModal.value = true;
};

const confirmClearAll = async (): Promise<void> => {
  clearingAll.value = true;

  try {
    // Clear compare list (this updates compareIds.value)
    await clearCompareList();

    // Force update with empty array - don't rely on compareIds.value as it might not be reactive yet
    await updateProductIds([]);

    // Clear URL params and stay on page
    router.replace({ query: {} });
  } catch (error: any) {
    console.error('Error clearing compare list:', error);
  } finally {
    clearingAll.value = false;
  }
};

const retryLoad = async (): Promise<void> => {
  const ids = getProductIds();
  if (ids.length > 0) {
    await updateProductIds(ids);
  } else {
    await fetchCompareData();
  }
};

// Watch for route changes (when navigating with query params)
watch(
  () => route.query.products,
  async (newProducts) => {
    if (newProducts) {
      const ids = (newProducts as string)
        .split(',')
        .map((id) => parseInt(id))
        .filter((id) => !isNaN(id));
      await updateProductIds(ids);
    }
  },
  { immediate: false }
);

const openAddProductModal = (): void => {
  showAddProductModal.value = true;
};

const closeAddProductModal = (): void => {
  showAddProductModal.value = false;
};

const handleProductsAdded = async (productIds: number[]): Promise<void> => {
  // Refresh compare data with new products
  const currentIds = compareIds.value || [];
  const allIds = [...new Set([...currentIds, ...productIds])];

  // Optimistically update URL first for instant feedback
  if (allIds.length > 0) {
    router.replace({ query: { products: allIds.join(',') } });
  }

  // Then update the data
  await updateProductIds(allIds);

  // Scroll to top to show new products
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(async () => {
  // Initialize with current product IDs
  const ids = getProductIds();
  if (ids.length > 0) {
    await updateProductIds(ids);
  } else {
    await fetchCompareData();
  }
});
</script>
