/**
 * Composable for Index Pages
 * Provides common functionality for list/index pages with filtering, sorting, and pagination
 */

import { reactive, computed, watch, type ComputedRef, type Ref } from 'vue';
import { useRouter, useRoute, type Router, type RouteLocationNormalizedLoaded } from 'vue-router';
import { debounceRouteUpdate } from '../utils/routeDebounce';
import { debounce } from '../utils/debounce';
import type { PaginationMeta } from '../../types/index';

interface UseIndexPageOptions {
  defaultSort?: string;
  defaultDir?: 'asc' | 'desc';
  defaultPerPage?: number;
  debounceMs?: number;
  extraFilters?: Record<string, any>;
}

interface UseIndexPageReturn<T = any> {
  items: ComputedRef<T[]>;
  loading: ComputedRef<boolean>;
  pagination: ComputedRef<PaginationMeta>;
  filters: {
    q: string;
    per_page: number;
    [key: string]: any;
  };
  sortField: { value: string };
  sortDir: { value: 'asc' | 'desc' };
  hasFilters: ComputedRef<boolean>;
  updateQueryParams: (page?: number) => void;
  fetchItems: (page?: number) => Promise<void>;
  applyFilters: () => void;
  resetFilters: () => void;
  sortBy: (field: string) => void;
  loadPage: (page: number) => void;
}

interface Store {
  items: Ref<any[]> | ComputedRef<any[]>;
  loading: Ref<boolean> | ComputedRef<boolean>;
  pagination: Ref<PaginationMeta> | ComputedRef<PaginationMeta>;
  fetchItems: (params: Record<string, any>) => Promise<any[]>;
}

export function useIndexPage<T = any>(
  store: Store,
  options: UseIndexPageOptions = {}
): UseIndexPageReturn<T> {
  const {
    defaultSort = 'id',
    defaultDir = 'desc',
    defaultPerPage = 5,
    debounceMs = 300,
  } = options;

  const router: Router = useRouter();
  const route: RouteLocationNormalizedLoaded = useRoute();

  // Reactive state from store
  const items = computed(() => store.items.value) as ComputedRef<T[]>;
  const loading = computed(() => store.loading.value);
  const pagination = computed(() => store.pagination.value);

  const sortField = reactive<{ value: string }>({
    value: (route.query.sort as string) || defaultSort,
  });
  const sortDir = reactive<{ value: 'asc' | 'desc' }>({
    value: (route.query.dir as 'asc' | 'desc') || defaultDir,
  });

  // Initialize filters from URL query params
  const filters = reactive<Record<string, any>>({
    q: (route.query.q as string) || '',
    per_page: parseInt((route.query.per_page as string) || String(defaultPerPage)) || defaultPerPage,
    ...options.extraFilters,
  });

  // Update URL query parameters with debouncing
  const updateQueryParams = (page: number = 1): void => {
    const query: Record<string, any> = {
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
      }, {} as Record<string, string>),
    };

    // Remove undefined values
    Object.keys(query).forEach((key) => {
      if (query[key] === undefined) {
        delete query[key];
      }
    });

    // Debounce route updates to prevent rapid router.replace calls
    debounceRouteUpdate(router, query);
  };

  // Fetch items function
  const fetchItems = async (page: number = 1): Promise<void> => {
    try {
      const params: Record<string, any> = {
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
        }, {} as Record<string, any>),
      };
      await store.fetchItems(params);
    } catch (error: any) {
      console.error('Error fetching items:', error);
      if (error.response?.status === 401) {
        window.location.href = '/login';
      }
    }
  };

  // Debounced fetch function to prevent rapid API calls
  const debouncedFetchItems = debounce((page: number) => {
    fetchItems(page);
  }, debounceMs);

  // Watch for per_page changes and automatically fetch
  watch(
    () => filters.per_page,
    () => {
      updateQueryParams(1);
      debouncedFetchItems(1);
    }
  );

  // Apply filters
  const applyFilters = (): void => {
    updateQueryParams(1);
    debouncedFetchItems(1);
  };

  // Reset filters
  const resetFilters = (): void => {
    filters.q = '';
    filters.per_page = defaultPerPage;
    sortField.value = defaultSort;
    sortDir.value = defaultDir;
    if (options.extraFilters) {
      Object.keys(options.extraFilters).forEach((key) => {
        filters[key] = options.extraFilters![key];
      });
    }
    router.replace({ query: {} });
    debouncedFetchItems(1);
  };

  // Sort by field
  const sortBy = (field: string): void => {
    if (sortField.value === field) {
      sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
      sortField.value = field;
      sortDir.value = 'asc';
    }
    const currentPage = (pagination.value as PaginationMeta)?.current_page || 1;
    updateQueryParams(currentPage);
    debouncedFetchItems(currentPage);
  };

  // Load page
  const loadPage = (page: number): void => {
    updateQueryParams(page);
    debouncedFetchItems(page);
  };

  // Check if filters are active
  const hasFilters = computed(() => {
    const hasSearch = filters.q && (filters.q as string).trim() !== '';
    const hasPerPage = filters.per_page !== defaultPerPage;
    const hasSort = sortField.value !== defaultSort || sortDir.value !== defaultDir;
    const hasExtraFilters = options.extraFilters
      ? Object.keys(options.extraFilters).some(
          (key) => filters[key] && filters[key] !== options.extraFilters![key]
        )
      : false;
    return hasSearch || hasPerPage || hasSort || hasExtraFilters;
  });

  return {
    items: items as ComputedRef<T[]>,
    loading: loading as ComputedRef<boolean>,
    pagination: pagination as ComputedRef<PaginationMeta>,
    filters: filters as { q: string; per_page: number; [key: string]: any },
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

