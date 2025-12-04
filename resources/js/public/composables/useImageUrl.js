/**
 * Composable for consistent image URL handling
 * @param {import('vue').Ref|Object} item - Product, partner, or category object
 * @returns {Object} Image URL computed properties
 */
import { computed } from 'vue';
import { getImageUrl } from '../utils';

export function useImageUrl(item) {
  const productImageUrl = computed(() => {
    if (!item.value) return null;
    return item.value.image_url ? getImageUrl(item.value.image_url) : null;
  });

  const partnerLogoUrl = computed(() => {
    if (!item.value?.partner) return 'https://placehold.co/64x64';
    return item.value.partner.logo_url
      ? getImageUrl(item.value.partner.logo_url, 'https://placehold.co/64x64')
      : 'https://placehold.co/64x64';
  });

  const categoryImageUrl = computed(() => {
    if (!item.value) return null;
    return item.value.image_url ? getImageUrl(item.value.image_url) : null;
  });

  return {
    productImageUrl,
    partnerLogoUrl,
    categoryImageUrl
  };
}

