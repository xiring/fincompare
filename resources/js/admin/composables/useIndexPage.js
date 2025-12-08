/**
 * Composable for Index Pages
 * Provides common functionality for list/index pages with filtering, sorting, and pagination
 */

import { reactive, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { debounceRouteUpdate } from '../utils/routeDebounce';
import { debounce } from '../utils/debounce';

export function useIndexPage(store, options = {}) {
  const {
    defaultSort = 'id',
    defaultDir = 'desc',
    defaultPerPage = 5,
    debounceMs = 300,
  } = options;

  const router = useRouter();
  const route = useRoute();

  // Reactive state from store
  const items = computed(() => store.items);
  const loading = computed(() => store.loading);
  const pagination = computed(() => store.pagination);

  const sortField = reactive({ value: route.query.sort || defaultSort });
  const sortDir = reactive({ value: route.query.dir || defaultDir });

  // Initialize filters from URL query params
  const filters = reactive({
    q: route.query.q || '',
    per_page: parseInt(route.query.per_page) || defaultPerPage,
    ...options.extraFilters,
  });

  // Update URL query parameters with debouncing
  const updateQueryParams = (page = 1) => {
    const query = {
      ...route.query,
      page: page > 1 ? page.toString() : undefined,
      q: filters.q || undefined,
      per_page: filters.per_page !== defaultPerPage ? filters.per_page.toString() : undefined,
      sort: sortField.value,
      dir: sortDir.value,
      ...Object.keys(options.extraFilters || {}).reduce((acc, key) => {
        const value = filters[key];
        if (value && value !== '') {
          acc[key] = value.toString();
        }
        return acc;
      }, {}),
    };

    // Remove undefined values
    Object.keys(query).forEach(key => {
      if (query[key] === undefined) {
        delete query[key];
      }
    });

    // Debounce route updates to prevent rapid router.replace calls
    debounceRouteUpdate(router, query);
  };

  // Fetch items function
  const fetchItems = async (page = 1) => {
    try {
      const params = {
        page,
        per_page: filters.per_page,
        q: filters.q,
        sort: sortField.value,
        dir: sortDir.value,
        ...Object.keys(options.extraFilters || {}).reduce((acc, key) => {
          if (filters[key]) {
            acc[key] = filters[key];
          }
          return acc;
        }, {}),
      };
      await store.fetchItems(params);
    } catch (error) {
      console.error('Error fetching items:', error);
      if (error.response?.status === 401) {
        window.location.href = '/login';
      }
    }
  };

  // Debounced fetch function to prevent rapid API calls
  const debouncedFetchItems = debounce((page) => {
    fetchItems(page);
  }, debounceMs);

  // Watch for per_page changes and automatically fetch
  watch(() => filters.per_page, () => {
    updateQueryParams(1);
    debouncedFetchItems(1);
  });

  // Apply filters
  const applyFilters = () => {
    updateQueryParams(1);
    debouncedFetchItems(1);
  };

  // Reset filters
  const resetFilters = () => {
    filters.q = '';
    filters.per_page = defaultPerPage;
    sortField.value = defaultSort;
    sortDir.value = defaultDir;
    if (options.extraFilters) {
      Object.keys(options.extraFilters).forEach(key => {
        filters[key] = options.extraFilters[key];
      });
    }
    router.replace({ query: {} });
    debouncedFetchItems(1);
  };

  // Sort by field
  const sortBy = (field) => {
    if (sortField.value === field) {
      sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
      sortField.value = field;
      sortDir.value = 'asc';
    }
    const currentPage = pagination.value?.current_page || 1;
    updateQueryParams(currentPage);
    debouncedFetchItems(currentPage);
  };

  // Load page
  const loadPage = (page) => {
    updateQueryParams(page);
    debouncedFetchItems(page);
  };

  // Check if filters are active
  const hasFilters = computed(() => {
    const hasSearch = filters.q && filters.q.trim() !== '';
    const hasPerPage = filters.per_page !== defaultPerPage;
    const hasSort = sortField.value !== defaultSort || sortDir.value !== defaultDir;
    const hasExtraFilters = options.extraFilters
      ? Object.keys(options.extraFilters).some(key => filters[key] && filters[key] !== options.extraFilters[key])
      : false;
    return hasSearch || hasPerPage || hasSort || hasExtraFilters;
  });

  return {
    items,
    loading,
    pagination,
    filters,
    sortField,
    sortDir,
    hasFilters,
    updateQueryParams,
    fetchItems,
    applyFilters,
    resetFilters,
    sortBy,
    loadPage,
  };
}

