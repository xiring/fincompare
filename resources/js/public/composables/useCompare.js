/**
 * Compare functionality composable
 * Manages product comparison state with localStorage sync
 */

import { ref, computed } from 'vue';
import { useLocalStorage } from './useLocalStorage';
import { webService } from '../services/api';
import { STORAGE_KEYS } from '../utils/constants';
import { useToastStore } from '../stores/toast';

const compareIds = useLocalStorage(STORAGE_KEYS.COMPARE_IDS, []);

export function useCompare() {
  const loading = ref(false);
  const error = ref(null);
  const toastStore = useToastStore();

  /**
   * Toggle product in compare list
   * @param {number} productId
   * @returns {Promise<boolean>}
   */
  const toggleCompare = async (productId) => {
    if (!productId || isNaN(productId)) {
      console.warn('Invalid product ID:', productId);
      return false;
    }

    const isSelected = compareIds.value.value.includes(productId);
    loading.value = true;
    error.value = null;

    try {
      const response = await webService.toggleCompare({
        id: productId,
        selected: !isSelected,
      });

      if (response.data?.ok) {
        if (!isSelected) {
          compareIds.value.value.push(productId);
          toastStore.success('Product added to compare');
        } else {
          compareIds.value.value = compareIds.value.value.filter(
            (id) => id !== productId
          );
          toastStore.success('Product removed from compare');
        }
        return true;
      }
      toastStore.error('Failed to update compare list');
      return false;
    } catch (err) {
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
   * @param {number} productId
   * @returns {boolean}
   */
  const isInCompare = (productId) => {
    return compareIds.value.value.includes(productId);
  };

  /**
   * Add product to compare
   * @param {number} productId
   * @returns {Promise<boolean>}
   */
  const addToCompare = async (productId) => {
    if (isInCompare(productId)) return true;
    return toggleCompare(productId);
  };

  /**
   * Remove product from compare
   * @param {number} productId
   * @returns {Promise<boolean>}
   */
  const removeFromCompare = async (productId) => {
    if (!isInCompare(productId)) return true;
    return toggleCompare(productId);
  };

  /**
   * Clear all compared products
   * @returns {Promise<void>}
   */
  const clearAll = async () => {
    const ids = [...compareIds.value.value];
    loading.value = true;
    error.value = null;

    try {
      // Remove all products from compare
      await Promise.all(
        ids.map((id) =>
          webService.toggleCompare({ id, selected: false }).catch(console.error)
        )
      );
      compareIds.value.value = [];
      toastStore.success('All products removed from compare');
    } catch (err) {
      error.value = err.message || 'Failed to clear compare list';
      console.error('Failed to clear compare:', err);
      toastStore.error('Failed to clear compare list');
    } finally {
      loading.value = false;
    }
  };

  /**
   * Get compare URL with product IDs
   * @returns {string}
   */
  const getCompareUrl = () => {
    const ids = compareIds.value.value.join(',');
    return ids ? `/compare?products=${ids}` : '/compare';
  };

  return {
    compareIds: computed(() => compareIds.value.value),
    loading: computed(() => loading.value),
    error: computed(() => error.value),
    compareCount: computed(() => compareIds.value.value.length),
    toggleCompare,
    isInCompare,
    addToCompare,
    removeFromCompare,
    clearAll,
    getCompareUrl,
  };
}
