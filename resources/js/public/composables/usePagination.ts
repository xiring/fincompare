/**
 * Pagination composable for infinite scroll and paginated data
 */

import { ref, computed, type Ref, type ComputedRef } from 'vue';

interface PaginationOptions {
  initialPage?: number;
  perPage?: number;
  infiniteScroll?: boolean;
}

interface PaginationResponse {
  data?: {
    total?: number;
    next_page_url?: string | null;
    current_page?: number;
  };
}

interface UsePaginationReturn {
  currentPage: Ref<number>;
  perPage: Ref<number>;
  totalItems: Ref<number>;
  totalPages: ComputedRef<number>;
  nextPageUrl: Ref<string | null>;
  loading: Ref<boolean>;
  hasMore: ComputedRef<boolean>;
  isFirstPage: ComputedRef<boolean>;
  isLastPage: ComputedRef<boolean>;
  nextPage: () => void;
  prevPage: () => void;
  goToPage: (page: number) => void;
  reset: () => void;
  updateFromResponse: (response: PaginationResponse) => void;
}

export function usePagination(options: PaginationOptions = {}): UsePaginationReturn {
  const {
    initialPage = 1,
    perPage = 12,
    infiniteScroll = false,
  } = options;

  const currentPage = ref<number>(initialPage);
  const perPageCount = ref<number>(perPage);
  const totalItems = ref<number>(0);
  const nextPageUrl = ref<string | null>(null);
  const loading = ref<boolean>(false);
  const hasMore = computed<boolean>(() => !!nextPageUrl.value);

  /**
   * Calculate total pages
   */
  const totalPages = computed<number>(() => {
    if (totalItems.value === 0) return 0;
    return Math.ceil(totalItems.value / perPageCount.value);
  });

  /**
   * Check if on first page
   */
  const isFirstPage = computed<boolean>(() => currentPage.value === 1);

  /**
   * Check if on last page
   */
  const isLastPage = computed<boolean>(() => currentPage.value >= totalPages.value);

  /**
   * Go to next page
   */
  const nextPage = (): void => {
    if (hasMore.value) {
      currentPage.value++;
    }
  };

  /**
   * Go to previous page
   */
  const prevPage = (): void => {
    if (currentPage.value > 1) {
      currentPage.value--;
    }
  };

  /**
   * Go to specific page
   */
  const goToPage = (page: number): void => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page;
    }
  };

  /**
   * Reset pagination
   */
  const reset = (): void => {
    currentPage.value = initialPage;
    totalItems.value = 0;
    nextPageUrl.value = null;
    loading.value = false;
  };

  /**
   * Update pagination state from API response
   */
  const updateFromResponse = (response: PaginationResponse): void => {
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

