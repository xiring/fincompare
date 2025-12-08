<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Products</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage product catalog</p>
      </div>
      <div class="flex items-center gap-3">
        <router-link
          to="/admin/products/import"
          class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-600 text-white rounded-lg font-medium text-sm hover:bg-amber-700 transition-colors"
        >
          <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
          </svg>
          Import
        </router-link>
        <router-link
          to="/admin/products/create"
          class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors"
        >
          <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          New Product
        </router-link>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by name"
          class="min-w-[200px] px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
        />
        <PerPageSelector v-model="filters.per_page" />
        <button
          type="submit"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors"
        >
          Apply
        </button>
        <button
          v-if="hasFilters"
          type="button"
          @click="resetFilters"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
        >
          Reset
        </button>
      </form>
    </div>

    <!-- Pagination (Above Table) -->
    <Pagination :pagination="pagination" @page-change="loadPage" class="mb-4" />

    <!-- Products Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Image</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                <button @click="sortBy('name')" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                  Name
                  <svg v-if="sortField === 'name'" class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                  </svg>
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Partner</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                <button @click="sortBy('status')" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                  Status
                  <svg v-if="sortField === 'status'" class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortDir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                  </svg>
                </button>
              </th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-if="loading" v-for="i in 5" :key="`skeleton-${i}`" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-12 w-12 bg-gray-200 dark:bg-gray-700 rounded-lg"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 ml-auto"></div></td>
            </tr>
            <tr v-else-if="products.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-12 text-gray-500 dark:text-gray-400">No products found</td>
            </tr>
            <tr v-else v-for="product in products" :key="product.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <img
                  v-if="product.image"
                  :src="`/storage/${product.image}`"
                  :alt="product.name"
                  class="h-12 w-12 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
                />
                <div v-else class="h-12 w-12 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                  <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ product.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ product.product_category?.name || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ product.partner?.name || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="product.status === 'active'
                    ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400'"
                >
                  {{ product.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/products/${product.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-600 hover:text-primary-900 hover:bg-primary-50 dark:hover:bg-primary-900/20 dark:text-primary-400 rounded-lg transition-colors"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </router-link>
                  <button
                    @click="handleDelete(product)"
                    title="Delete"
                    class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 rounded-lg transition-colors"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useProductsStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';

const router = useRouter();
const productsStore = useProductsStore();

// Reactive state from store
const products = computed(() => productsStore.items);
const loading = computed(() => productsStore.loading);
const pagination = computed(() => productsStore.pagination);

const sortField = reactive({ value: 'name' });
const sortDir = reactive({ value: 'asc' });

const filters = reactive({
  q: '',
  per_page: 5
});

const hasFilters = computed(() => {
  return filters.q || filters.per_page !== 5;
});

// Watch for per_page changes and automatically fetch
watch(() => filters.per_page, () => {
  fetchProducts(1);
});

const fetchProducts = async (page = 1) => {
  try {
    const params = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      sort: sortField.value,
      dir: sortDir.value
    };
    await productsStore.fetchItems(params);
  } catch (error) {
    console.error('Error fetching products:', error);
    if (error.response?.status === 401) {
      window.location.href = '/login';
    }
  }
};

const applyFilters = () => {
  fetchProducts(1);
};

const resetFilters = () => {
  filters.q = '';
  filters.per_page = 5;
  fetchProducts(1);
};

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDir.value = 'asc';
  }
  fetchProducts(pagination.value?.current_page || 1);
};

const loadPage = (page) => {
  fetchProducts(page);
};

const handleDelete = async (product) => {
  if (!confirm(`Delete product "${product.name}"?`)) return;

  try {
    await productsStore.deleteItem(product.id);
    // Store automatically updates the list, but we may need to refresh if pagination changed
    if (products.value.length === 0 && pagination.value.current_page > 1) {
      fetchProducts(pagination.value.current_page - 1);
    }
  } catch (error) {
    console.error('Error deleting product:', error);
    alert('Failed to delete product');
  }
};

onMounted(() => {
  fetchProducts();
});
</script>

