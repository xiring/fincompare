/**
 * Home page data composable
 */

import { computed, type ComputedRef } from 'vue';
import { useAsyncData } from './useAsyncData';
import { apiService } from '../services/api';
import type { Product, ProductCategory, Partner } from '../types/index';

interface HomeData {
  featuredProducts: Product[];
  categories: ProductCategory[];
  partners: Partner[];
}

export function useHomeData() {
  const {
    data,
    loading,
    error,
    hasData,
    hasError,
    execute: fetchHomeData,
    refresh,
  } = useAsyncData<HomeData>(async () => {
    const response = await apiService.getHomeData();
    return {
      featuredProducts: (response.data as any)?.featured_products || [],
      categories: (response.data as any)?.categories || [],
      partners: (response.data as any)?.partners || [],
    };
  });

  return {
    featuredProducts: computed(() => data.value?.featuredProducts || []) as ComputedRef<Product[]>,
    categories: computed(() => data.value?.categories || []) as ComputedRef<ProductCategory[]>,
    partners: computed(() => data.value?.partners || []) as ComputedRef<Partner[]>,
    loading,
    error,
    hasData,
    hasError,
    fetchHomeData,
    refresh,
  };
}

