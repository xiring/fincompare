/**
 * Compare functionality composable
 * Manages product comparison state with localStorage sync
 */

import { ref, computed, type ComputedRef } from 'vue';
import { useLocalStorage } from './useLocalStorage';
import { webService } from '../services/api';
import { STORAGE_KEYS } from '../utils/constants';
import { useToastStore } from '../stores/toast';

const compareIds = useLocalStorage<number[]>(STORAGE_KEYS.COMPARE_IDS, []);

export function useCompare() {
  const loading = ref<boolean>(false);
  const error = ref<string | null>(null);
  const toastStore = useToastStore();

  /**
   * Toggle product in compare list
   */
  const toggleCompare = async (productId: number): Promise<boolean> => {
    if (!productId || isNaN(productId)) {
      console.warn('Invalid product ID:', productId);
      return false;
    }

    const isSelected = compareIds.value.value.includes(productId);
    loading.value = true;
    error.value = null;

    try {
      const response = await webService.toggleCompare({
        product_id: productId,
      });

      if ((response.data as any)?.ok) {
        if (!isSelected) {
          compareIds.value.value.push(productId);
          toastStore.success('Product added to compare');
        } else {
          compareIds.value.value = compareIds.value.value.filter((id) => id !== productId);
          toastStore.success('Product removed from compare');
        }
        return true;
      }
      toastStore.error('Failed to update compare list');
      return false;
    } catch (err: any) {
      error.value = err.message || 'Failed to toggle compare';
      console.error('Failed to toggle compare:', err);
      toastStore.error('Failed to update compare list');
      return false;
    } finally {
      loading.value = false;
    }
  };

  /**
   * Check if product is in compare list
   */
  const isInCompare = (productId: number): boolean => {
    return compareIds.value.value.includes(productId);
  };

  /**
   * Add product to compare
   */
  const addToCompare = async (productId: number): Promise<boolean> => {
    if (isInCompare(productId)) return true;
    return toggleCompare(productId);
  };

  /**
   * Remove product from compare
   */
  const removeFromCompare = async (productId: number): Promise<boolean> => {
    if (!isInCompare(productId)) return true;
    return toggleCompare(productId);
  };

  /**
   * Clear all compared products
   */
  const clearAll = async (): Promise<void> => {
    const ids = [...compareIds.value.value];
    loading.value = true;
    error.value = null;

    try {
      // Remove all products from compare
      await Promise.all(
        ids.map((id) =>
          webService.toggleCompare({ product_id: id }).catch(console.error)
        )
      );
      compareIds.value.value = [];
      toastStore.success('All products removed from compare');
    } catch (err: any) {
      error.value = err.message || 'Failed to clear compare list';
      console.error('Failed to clear compare:', err);
      toastStore.error('Failed to clear compare list');
    } finally {
      loading.value = false;
    }
  };

  /**
   * Get compare URL with product IDs
   */
  const getCompareUrl = (): string => {
    const ids = compareIds.value.value.join(',');
    return ids ? `/compare?products=${ids}` : '/compare';
  };

  return {
    compareIds: computed(() => compareIds.value.value) as ComputedRef<number[]>,
    loading: computed(() => loading.value) as ComputedRef<boolean>,
    error: computed(() => error.value) as ComputedRef<string | null>,
    compareCount: computed(() => compareIds.value.value.length) as ComputedRef<number>,
    toggleCompare,
    isInCompare,
    addToCompare,
    removeFromCompare,
    clearAll,
    getCompareUrl,
  };
}

