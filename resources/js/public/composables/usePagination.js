/**
 * Pagination composable for infinite scroll and paginated data
 */

import { ref, computed } from 'vue';

/**
 * @typedef {Object} PaginationOptions
 * @property {number} initialPage
 * @property {number} perPage
 * @property {boolean} infiniteScroll
 */

/**
 * @param {PaginationOptions} options
 * @returns {Object}
 */
export function usePagination(options = {}) {
  const {
    initialPage = 1,
    perPage = 12,
    infiniteScroll = false,
  } = options;

  const currentPage = ref(initialPage);
  const perPageCount = ref(perPage);
  const totalItems = ref(0);
  const nextPageUrl = ref(null);
  const loading = ref(false);
  const hasMore = computed(() => !!nextPageUrl.value);

  /**
   * Calculate total pages
   */
  const totalPages = computed(() => {
    if (totalItems.value === 0) return 0;
    return Math.ceil(totalItems.value / perPageCount.value);
  });

  /**
   * Check if on first page
   */
  const isFirstPage = computed(() => currentPage.value === 1);

  /**
   * Check if on last page
   */
  const isLastPage = computed(() => currentPage.value >= totalPages.value);

  /**
   * Go to next page
   */
  const nextPage = () => {
    if (hasMore.value) {
      currentPage.value++;
    }
  };

  /**
   * Go to previous page
   */
  const prevPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--;
    }
  };

  /**
   * Go to specific page
   */
  const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page;
    }
  };

  /**
   * Reset pagination
   */
  const reset = () => {
    currentPage.value = initialPage;
    totalItems.value = 0;
    nextPageUrl.value = null;
    loading.value = false;
  };

  /**
   * Update pagination state from API response
   */
  const updateFromResponse = (response) => {
    if (response.data) {
      totalItems.value = response.data.total || 0;
      nextPageUrl.value = response.data.next_page_url || null;
      currentPage.value = response.data.current_page || 1;
    }
  };

  return {
    currentPage,
    perPage: perPageCount,
    totalItems,
    totalPages,
    nextPageUrl,
    loading,
    hasMore,
    isFirstPage,
    isLastPage,
    nextPage,
    prevPage,
    goToPage,
    reset,
    updateFromResponse,
  };
}

