/**
 * Home page data composable
 */

import { computed } from 'vue';
import { useAsyncData } from './useAsyncData';
import { apiService } from '../services/api';

export function useHomeData() {
  const {
    data,
    loading,
    error,
    hasData,
    hasError,
    execute: fetchHomeData,
    refresh,
  } = useAsyncData(async () => {
    const response = await apiService.getHomeData();
    return {
      featuredProducts: response.data.featured_products || [],
      categories: response.data.categories || [],
      partners: response.data.partners || [],
    };
  });

  return {
    featuredProducts: computed(() => data.value?.featuredProducts || []),
    categories: computed(() => data.value?.categories || []),
    partners: computed(() => data.value?.partners || []),
    loading,
    error,
    hasData,
    hasError,
    fetchHomeData,
    refresh,
  };
}
