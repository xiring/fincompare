/**
 * Products data composable with pagination and filtering
 */

import { ref, computed } from 'vue';
import { useAsyncData } from './useAsyncData';
import { usePagination } from './usePagination';
import { apiService } from '../services/api';
import { PAGINATION } from '../utils/constants';

export function useProducts(options = {}) {
  const { perPage = PAGINATION.PRODUCTS_PER_PAGE, immediate = false } = options;

  const filters = ref({
    q: '',
    category: '',
    partner_id: '',
    featured: false,
  });

  const pagination = usePagination({ perPage, infiniteScroll: true });

  const {
    data,
    loading,
    error,
    execute: fetchProducts,
    refresh,
  } = useAsyncData(
    async () => {
      const params = {
        page: pagination.currentPage.value,
        per_page: pagination.perPage.value,
        ...filters.value,
      };

      // Remove empty filters
      Object.keys(params).forEach((key) => {
        if (params[key] === '' || params[key] === false) {
          delete params[key];
        }
      });

      const response = await apiService.getProducts(params);

      // Update pagination from response
      if (response.data.products) {
        pagination.updateFromResponse({ data: response.data.products });
      }

      return {
        products: response.data.products?.data || [],
        categories: response.data.categories || [],
        partners: response.data.partners || [],
        category: response.data.category || null,
      };
    },
    { immediate }
  );

  /**
   * Apply filters and reset to first page
   */
  const applyFilters = (newFilters = {}) => {
    filters.value = { ...filters.value, ...newFilters };
    pagination.reset();
    return fetchProducts();
  };

  /**
   * Reset all filters
   */
  const resetFilters = () => {
    filters.value = {
      q: '',
      category: '',
      partner_id: '',
      featured: false,
    };
    pagination.reset();
    return fetchProducts();
  };

  /**
   * Load more products (infinite scroll)
   */
  const loadMore = async () => {
    if (!pagination.hasMore.value || loading.value) return;

    pagination.nextPage();
    const params = {
      page: pagination.currentPage.value,
      per_page: pagination.perPage.value,
      ...filters.value,
    };

    try {
      const response = await apiService.getProducts(params);
      if (response.data.products) {
        const newProducts = response.data.products.data || [];
        data.value.products.push(...newProducts);
        pagination.updateFromResponse({ data: response.data.products });
      }
    } catch (err) {
      console.error('Failed to load more products:', err);
      pagination.prevPage(); // Revert page increment on error
    }
  };

  return {
    products: computed(() => data.value?.products || []),
    categories: computed(() => data.value?.categories || []),
    partners: computed(() => data.value?.partners || []),
    category: computed(() => data.value?.category || null),
    filters,
    loading,
    error,
    pagination,
    fetchProducts,
    refresh,
    applyFilters,
    resetFilters,
    loadMore,
  };
}

