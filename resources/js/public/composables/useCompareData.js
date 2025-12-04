/**
 * Compare data composable
 */

import { computed, ref } from 'vue';
import { apiService } from '../services/api';
import { getImageUrl } from '../utils/helpers';

export function useCompareData(initialProductIds = []) {
  const productIds = ref(Array.isArray(initialProductIds) ? initialProductIds : []);
  const data = ref(null);
  const loading = ref(false);
  const error = ref(null);

  /**
   * Fetch compare data
   */
  const fetchCompareData = async () => {
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
        products: response.data.productsData || response.data.products || [],
        features: response.data.featuresData || response.data.features || [],
        values: response.data.values || {},
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
  const refresh = () => fetchCompareData();

  const products = computed(() => data.value?.products || []);
  const features = computed(() => data.value?.features || []);
  const values = computed(() => data.value?.values || {});

  /**
   * Get product image URL
   */
  const getProductImage = (product) => {
    if (product.image_url) return getImageUrl(product.image_url);
    if (product.partner?.logo_url) return getImageUrl(product.partner.logo_url);
    return 'https://placehold.co/28x28';
  };

  /**
   * Get attribute value for product
   */
  const getValue = (productId, key) => {
    return (values.value[productId] && values.value[productId][key]) || '—';
  };

  /**
   * Check if values differ for highlighting
   */
  const hasDifferentValues = (key) => {
    const vals = products.value.map((p) => getValue(p.id, key));
    const unique = new Set(vals.map((v) => JSON.stringify(v)));
    return unique.size > 1;
  };

  /**
   * Update product IDs and refetch
   */
  const updateProductIds = async (ids) => {
    productIds.value = Array.isArray(ids) ? ids : [];
    // Always fetch fresh data with new IDs
    return fetchCompareData();
  };

  return {
    products,
    features,
    values,
    loading: computed(() => loading.value),
    error: computed(() => error.value),
    productIds: computed(() => productIds.value),
    fetchCompareData,
    refresh,
    updateProductIds,
    getProductImage,
    getValue,
    hasDifferentValues,
  };
}
