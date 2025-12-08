/**
 * Compare data composable
 */

import { computed, ref, type Ref, type ComputedRef } from 'vue';
import { apiService } from '../services/api';
import { getImageUrl } from '../utils/helpers';
import type { Product } from '../types/index';

interface CompareData {
  products: Product[];
  features: any[];
  values: Record<number, Record<string, any>>;
}

export function useCompareData(initialProductIds: number[] = []) {
  const productIds = ref<number[]>(Array.isArray(initialProductIds) ? initialProductIds : []);
  const data = ref<CompareData | null>(null);
  const loading = ref<boolean>(false);
  const error = ref<any>(null);

  /**
   * Fetch compare data
   */
  const fetchCompareData = async (): Promise<CompareData> => {
    loading.value = true;
    error.value = null;

    try {
      // Always use current productIds.value (don't fallback to initialProductIds)
      const ids = productIds.value || [];

      // If no IDs, set empty data immediately
      if (ids.length === 0) {
        data.value = {
          products: [],
          features: [],
          values: {},
        };
        loading.value = false;
        return data.value;
      }

      const params = { products: ids.join(',') };
      const response = await apiService.getCompareData(params);

      data.value = {
        products: (response.data as any)?.productsData || (response.data as any)?.products || [],
        features: (response.data as any)?.featuresData || (response.data as any)?.features || [],
        values: (response.data as any)?.values || {},
      };

      return data.value;
    } catch (err) {
      error.value = err;
      console.error('Failed to fetch compare data:', err);
      data.value = {
        products: [],
        features: [],
        values: {},
      };
      throw err;
    } finally {
      loading.value = false;
    }
  };

  /**
   * Refresh data
   */
  const refresh = (): Promise<CompareData> => fetchCompareData();

  const products = computed(() => data.value?.products || []) as ComputedRef<Product[]>;
  const features = computed(() => data.value?.features || []) as ComputedRef<any[]>;
  const values = computed(() => data.value?.values || {}) as ComputedRef<Record<number, Record<string, any>>>;

  /**
   * Get product image URL
   */
  const getProductImage = (product: any): string => {
    if (product.image_url) return getImageUrl(product.image_url);
    if (product.partner?.logo_url) return getImageUrl(product.partner.logo_url);
    return 'https://placehold.co/28x28';
  };

  /**
   * Get attribute value for product
   */
  const getValue = (productId: number, key: string): any => {
    return (values.value[productId] && values.value[productId][key]) || '—';
  };

  /**
   * Check if values differ for highlighting
   */
  const hasDifferentValues = (key: string): boolean => {
    const vals = products.value.map((p) => getValue(p.id as number, key));
    const unique = new Set(vals.map((v) => JSON.stringify(v)));
    return unique.size > 1;
  };

  /**
   * Update product IDs and refetch
   */
  const updateProductIds = async (ids: number[]): Promise<CompareData> => {
    productIds.value = Array.isArray(ids) ? ids : [];
    // Always fetch fresh data with new IDs
    return fetchCompareData();
  };

  return {
    products,
    features,
    values,
    loading: computed(() => loading.value) as ComputedRef<boolean>,
    error: computed(() => error.value) as ComputedRef<any>,
    productIds: computed(() => productIds.value) as ComputedRef<number[]>,
    fetchCompareData,
    refresh,
    updateProductIds,
    getProductImage,
    getValue,
    hasDifferentValues,
  };
}

