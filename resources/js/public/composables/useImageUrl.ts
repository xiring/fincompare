/**
 * Composable for consistent image URL handling
 */

import { computed, type Ref } from 'vue';
import { getImageUrl } from '../utils/helpers';

export function useImageUrl<T extends { image_url?: string; partner?: { logo_url?: string } }>(
  item: Ref<T | null>
) {
  const productImageUrl = computed<string | null>(() => {
    if (!item.value) return null;
    return item.value.image_url ? getImageUrl(item.value.image_url) : null;
  });

  const partnerLogoUrl = computed<string>(() => {
    if (!item.value?.partner) return 'https://placehold.co/64x64';
    return item.value.partner.logo_url
      ? getImageUrl(item.value.partner.logo_url, 'https://placehold.co/64x64')
      : 'https://placehold.co/64x64';
  });

  const categoryImageUrl = computed<string | null>(() => {
    if (!item.value) return null;
    return item.value.image_url ? getImageUrl(item.value.image_url) : null;
  });

  return {
    productImageUrl,
    partnerLogoUrl,
    categoryImageUrl,
  };
}

