/**
 * Products data composable with pagination and filtering
 */

import { ref, computed, type Ref, type ComputedRef } from 'vue';
import { useAsyncData } from './useAsyncData';
import { usePagination } from './usePagination';
import { apiService } from '../services/api';
import { PAGINATION } from '../utils/constants';
import type { Product, ProductCategory, Partner } from '../types/index';

interface UseProductsOptions {
  perPage?: number;
  immediate?: boolean;
}

interface ProductsData {
  products: Product[];
  categories: ProductCategory[];
  partners: Partner[];
  category: ProductCategory | null;
}

interface ProductsFilters {
  q: string;
  category: string;
  partner_id: string;
  featured: boolean;
}

export function useProducts(options: UseProductsOptions = {}) {
  const { perPage = PAGINATION.PRODUCTS_PER_PAGE, immediate = false } = options;

  const filters = ref<ProductsFilters>({
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
  } = useAsyncData<ProductsData>(
    async () => {
      const params: Record<string, any> = {
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
      if ((response.data as any)?.products) {
        pagination.updateFromResponse({ data: (response.data as any).products });
      }

      return {
        products: (response.data as any)?.products?.data || [],
        categories: (response.data as any)?.categories || [],
        partners: (response.data as any)?.partners || [],
        category: (response.data as any)?.category || null,
      };
    },
    { immediate }
  );

  /**
   * Apply filters and reset to first page
   */
  const applyFilters = async (newFilters: Partial<ProductsFilters> = {}): Promise<ProductsData | null> => {
    filters.value = { ...filters.value, ...newFilters };
    pagination.reset();
    return fetchProducts();
  };

  /**
   * Reset all filters
   */
  const resetFilters = async (): Promise<ProductsData | null> => {
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
  const loadMore = async (): Promise<void> => {
    if (!pagination.hasMore.value || loading.value) return;

    pagination.nextPage();
    const params: Record<string, any> = {
      page: pagination.currentPage.value,
      per_page: pagination.perPage.value,
      ...filters.value,
    };

    try {
      const response = await apiService.getProducts(params);
      if ((response.data as any)?.products) {
        const newProducts = (response.data as any).products.data || [];
        if (data.value) {
          data.value.products.push(...newProducts);
        }
        pagination.updateFromResponse({ data: (response.data as any).products });
      }
    } catch (err) {
      console.error('Failed to load more products:', err);
      pagination.prevPage(); // Revert page increment on error
    }
  };

  return {
    products: computed(() => data.value?.products || []) as ComputedRef<Product[]>,
    categories: computed(() => data.value?.categories || []) as ComputedRef<ProductCategory[]>,
    partners: computed(() => data.value?.partners || []) as ComputedRef<Partner[]>,
    category: computed(() => data.value?.category || null) as ComputedRef<ProductCategory | null>,
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

