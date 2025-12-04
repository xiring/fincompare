<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">Compare Products</h1>
        <p class="mt-2 text-white/90">See specs side-by-side. Toggle highlights to spot differences quickly.</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div v-if="loading" class="bg-white border rounded-2xl p-12 text-center">
        <div class="animate-pulse">
          <div class="h-8 bg-gray-200 rounded w-1/2 mx-auto mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-1/3 mx-auto"></div>
        </div>
      </div>
      <div v-else-if="products.length === 0" class="bg-white border rounded-2xl p-12 text-center">
        <EmptyBoxIcon className="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-4 text-lg font-medium text-gray-900">No products to compare</h3>
        <p class="mt-2 text-sm text-gray-500">Add products to your compare list to see them side by side.</p>
        <router-link
          to="/products"
          class="mt-6 inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white transition-colors shadow-sm btn-brand-primary"
          style="color: #ffffff !important;"
        >
          Browse Products
        </router-link>
      </div>

      <div v-else>
        <!-- Controls Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 p-4 bg-white border rounded-2xl shadow-sm">
          <div class="flex flex-wrap items-center gap-3">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
              <input
                type="checkbox"
                v-model="highlightDiff"
                aria-label="Highlight differences between products"
                class="w-4 h-4 rounded border-gray-300 text-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)] focus:ring-offset-2 cursor-pointer"
              >
              <span>Highlight differences</span>
            </label>
            <div class="h-4 w-px bg-gray-300"></div>
            <button
              @click="clearAll"
              type="button"
              aria-label="Clear all products from compare"
              class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-sm bg-white text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-[color:var(--brand-primary)] focus:ring-offset-2"
            >
              Clear all
            </button>
          </div>
          <router-link
            to="/products"
            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-white font-semibold transition-all shadow-sm hover:shadow-md btn-brand-primary"
            style="color: #ffffff !important;"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #ffffff;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span style="color: #ffffff !important;">Add more products</span>
          </router-link>
        </div>

        <!-- Desktop Table View -->
        <div class="overflow-x-auto bg-white border rounded-2xl shadow-sm hidden md:block animate-fade-in-up">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider sticky left-0 bg-gradient-to-r from-gray-50 to-gray-100 z-20 border-r border-gray-200">
                  Feature
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
                          :aria-label="`Remove ${p.name || 'product'} from compare`"
                          class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                          title="Remove from compare"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                        </button>
                      </div>
                      <div class="flex gap-2">
                        <router-link
                          :to="`/products/${p.slug || p.id}`"
                          class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                          View Details
                        </router-link>
                        <router-link
                          :to="`/lead?product=${p.slug || p.id}`"
                          class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] rounded-lg transition-colors shadow-sm"
                          style="color: #ffffff !important;"
                        >
                          Apply
                        </router-link>
                      </div>
                    </div>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="f in features" :key="f.key" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-gray-900 sticky left-0 bg-white z-10 border-r border-gray-200 whitespace-nowrap">
                  {{ f.label }}
                </td>
                <td
                  v-for="p in products"
                  :key="p.id + '-' + f.key"
                  :class="getCellClass(p.id, f.key)"
                  class="px-6 py-4 text-sm text-gray-700 align-top"
                >
                  <span class="font-medium">{{ formatValue(getValue(p.id, f.key)) }}</span>
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
                  :aria-label="`Remove ${p.name || 'product'} from compare`"
                  class="flex-shrink-0 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <dd class="text-sm font-semibold text-gray-900 text-right">
                  {{ formatValue(getValue(p.id, f.key)) }}
                </dd>
              </div>
            </dl>
          </div>
        </div>

      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCompare, useCompareData, useSEO } from '../../composables';
import { getImageUrl } from '../../utils/helpers';
import { EmptyBoxIcon } from '../../components/icons';
import GuestLayout from '../../layouts/GuestLayout.vue';

const route = useRoute();
const router = useRouter();
const highlightDiff = ref(true);

const { toggleCompare, clearAll: clearCompareList, compareIds } = useCompare();

// Get product IDs from route or compareIds
const getProductIds = () => {
  if (route.query.products) {
    return route.query.products.split(',').map(id => parseInt(id)).filter(id => !isNaN(id));
  }
  return compareIds.value || [];
};

const initialProductIds = getProductIds();
const { products, features, values, loading, fetchCompareData, updateProductIds, getProductImage, getValue, hasDifferentValues } = useCompareData(initialProductIds);

useSEO({
  title: 'Compare Products',
  description: 'Compare multiple financial products side-by-side. View features, rates, and eligibility criteria to make an informed decision.',
  keywords: ['compare products', 'product comparison', 'financial comparison', 'compare loans', 'compare credit cards']
});

const getCellClass = (productId, key) => {
  if (!highlightDiff.value) return '';
  return hasDifferentValues(key) ? 'bg-amber-50/50 border-l-2 border-amber-400' : '';
};

const getMobileRowClass = (productId, key) => {
  if (!highlightDiff.value) return '';
  return hasDifferentValues(key) ? 'bg-amber-50/50' : '';
};

/**
 * Format value for display
 */
const formatValue = (value) => {
  if (value === '—' || value === null || value === undefined || value === '') {
    return '—';
  }

  // If it's a number, format it
  if (typeof value === 'number' || !isNaN(parseFloat(value))) {
    const num = parseFloat(value);
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

  return value;
};

const removeProduct = async (id) => {
  const success = await toggleCompare(id);
  if (success) {
    // compareIds is already updated by toggleCompare, use it directly
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
};

const clearAll = async () => {
  if (!confirm('Clear all products from comparison?')) return;

  try {
    // Clear compare list (this updates compareIds.value)
    await clearCompareList();

    // Force update with empty array - don't rely on compareIds.value as it might not be reactive yet
    await updateProductIds([]);

    // Clear URL params and stay on page
    router.replace({ query: {} });
  } catch (error) {
    console.error('Error clearing compare list:', error);
  }
};

// Watch for route changes (when navigating with query params)
watch(() => route.query.products, async (newProducts) => {
  if (newProducts) {
    const ids = newProducts.split(',').map(id => parseInt(id)).filter(id => !isNaN(id));
    await updateProductIds(ids);
  }
}, { immediate: false });

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
