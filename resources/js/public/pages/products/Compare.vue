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
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">No products to compare</h3>
        <p class="mt-2 text-sm text-gray-500">Add products to your compare list to see them side by side.</p>
        <router-link to="/products" class="mt-6 inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white transition-colors shadow-sm btn-brand-primary">
          Browse Products
        </router-link>
      </div>

      <div v-else>
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-3">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input
                type="checkbox"
                v-model="highlightDiff"
                class="rounded border-gray-300 accent-[color:var(--brand-primary)]"
              >
              Highlight differences
            </label>
            <button
              @click="clearAll"
              type="button"
              class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-gray-300 text-sm bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors"
            >
              Clear all
            </button>
          </div>
          <router-link to="/products" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-white font-semibold transition-colors shadow-sm btn-brand-primary">
            Add more products
          </router-link>
        </div>

        <!-- Desktop Table View -->
        <div class="overflow-x-auto bg-white border rounded-2xl hidden md:block animate-fade-in-up">
          <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 sticky left-0 bg-gray-50 z-10">Feature</th>
                <th v-for="p in products" :key="p.id" class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                  <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2">
                        <img :src="getProductImage(p)" :alt="p.name || 'Product'" class="w-10 h-10 rounded bg-gray-100 object-cover">
                        <span>{{ p.name || 'Product' }}</span>
                      </div>
                      <button @click="removeProduct(p.id)" class="text-xs text-red-600 hover:underline">Remove</button>
                    </div>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr v-for="f in features" :key="f.key">
                <td class="px-4 py-3 text-sm font-medium text-gray-900 sticky left-0 bg-white z-10">{{ f.label }}</td>
                <td
                  v-for="p in products"
                  :key="p.id + '-' + f.key"
                  :class="getCellClass(p.id, f.key)"
                  class="px-4 py-3 text-sm align-top"
                >
                  {{ getValue(p.id, f.key) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Stacked View -->
        <div class="md:hidden space-y-4">
          <div v-for="p in products" :key="p.id" class="bg-white border rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-2">
                <img :src="getProductImage(p)" :alt="p.name || 'Product'" class="w-10 h-10 rounded bg-gray-100 object-cover">
                <div class="font-semibold">{{ p.name || 'Product' }}</div>
              </div>
              <button @click="removeProduct(p.id)" class="text-xs text-red-600 hover:underline">Remove</button>
            </div>
            <dl class="divide-y">
              <div v-for="f in features" :key="'m-'+p.id+'-'+f.key" class="py-2 grid grid-cols-3 gap-3">
                <dt class="text-sm text-gray-600 col-span-1">{{ f.label }}</dt>
                <dd class="text-sm text-gray-900 col-span-2">{{ getValue(p.id, f.key) }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <div class="mt-4 flex justify-end">
          <router-link to="/products" class="inline-flex items-center text-sm text-[color:var(--brand-primary)] hover:underline font-medium">
            Add more products
          </router-link>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCompare, useCompareData, useSEO } from '../../composables';
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
  if (!highlightDiff.value) return 'text-gray-700';
  return hasDifferentValues(key) ? 'bg-yellow-50 text-gray-900' : 'text-gray-700';
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
