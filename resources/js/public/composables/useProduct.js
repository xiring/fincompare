/**
 * Single product composable
 */

import { computed } from 'vue';
import { useAsyncData } from './useAsyncData';
import { apiService } from '../services/api';
import { getImageUrl } from '../utils/helpers';

export function useProduct(idOrSlug) {
  const {
    data,
    loading,
    error,
    execute: fetchProduct,
    refresh,
  } = useAsyncData(async () => {
    const response = await apiService.getProduct(idOrSlug);
    return {
      product: response.data.product || null,
      attributes: response.data.attributes || [],
    };
  });

  const product = computed(() => data.value?.product || null);
  const attributes = computed(() => data.value?.attributes || []);

  const productImageUrl = computed(() => {
    if (!product.value) return null;
    return getImageUrl(product.value.image_url);
  });

  const partnerLogoUrl = computed(() => {
    if (!product.value?.partner?.logo_url) return null;
    return getImageUrl(product.value.partner.logo_url);
  });

  return {
    product,
    attributes,
    productImageUrl,
    partnerLogoUrl,
    loading,
    error,
    fetchProduct,
    refresh,
  };
}

