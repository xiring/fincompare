/**
 * Single product composable
 */

import { computed, type ComputedRef } from 'vue';
import { useAsyncData } from './useAsyncData';
import { apiService } from '../services/api';
import { getImageUrl } from '../utils/helpers';
import type { Product, ProductAttribute } from '../types/index';

interface ProductData {
  product: Product | null;
  attributes: ProductAttribute[];
}

export function useProduct(idOrSlug: string | number) {
  const {
    data,
    loading,
    error,
    execute: fetchProduct,
    refresh,
  } = useAsyncData<ProductData>(async () => {
    const response = await apiService.getProduct(idOrSlug);
    return {
      product: (response.data as any)?.product || null,
      attributes: (response.data as any)?.attributes || [],
    };
  });

  const product = computed(() => data.value?.product || null) as ComputedRef<Product | null>;
  const attributes = computed(() => data.value?.attributes || []) as ComputedRef<ProductAttribute[]>;

  const productImageUrl = computed(() => {
    if (!product.value) return null;
    return getImageUrl((product.value as any).image_url);
  });

  const partnerLogoUrl = computed(() => {
    if (!product.value || !(product.value as any).partner?.logo_url) return null;
    return getImageUrl((product.value as any).partner.logo_url);
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

