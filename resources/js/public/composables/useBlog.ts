/**
 * Blog posts composable with pagination and filtering
 */

import { ref, computed, type Ref, type ComputedRef } from 'vue';
import { useAsyncData } from './useAsyncData';
import { usePagination } from './usePagination';
import { apiService } from '../services/api';
import { PAGINATION } from '../utils/constants';
import type { BlogPost } from '../types/index';

interface UseBlogOptions {
  perPage?: number;
  immediate?: boolean;
}

interface BlogData {
  posts: BlogPost[];
  categories: string[];
  tags: string[];
}

interface BlogFilters {
  q: string;
  category: string;
  tag: string;
  sort: 'asc' | 'desc';
}

export function useBlog(options: UseBlogOptions = {}) {
  const { perPage = PAGINATION.BLOG_PER_PAGE, immediate = false } = options;

  const filters = ref<BlogFilters>({
    q: '',
    category: '',
    tag: '',
    sort: 'desc',
  });

  const pagination = usePagination({ perPage, infiniteScroll: true });

  const {
    data,
    loading,
    error,
    execute: fetchPosts,
    refresh,
  } = useAsyncData<BlogData>(
    async () => {
      const params: Record<string, any> = {
        page: pagination.currentPage.value,
        per_page: pagination.perPage.value,
        ...filters.value,
      };

      // Remove empty filters
      Object.keys(params).forEach((key) => {
        if (params[key] === '') {
          delete params[key];
        }
      });

      const response = await apiService.getBlogPosts(params);

      // Update pagination from response
      if ((response.data as any)?.posts) {
        pagination.updateFromResponse({ data: (response.data as any).posts });
      }

      return {
        posts: (response.data as any)?.posts?.data || [],
        categories: (response.data as any)?.categories || [],
        tags: (response.data as any)?.tags || [],
      };
    },
    { immediate }
  );

  /**
   * Apply filters and reset to first page
   */
  const applyFilters = async (newFilters: Partial<BlogFilters> = {}): Promise<BlogData | null> => {
    filters.value = { ...filters.value, ...newFilters };
    pagination.reset();
    return fetchPosts();
  };

  /**
   * Load more posts (infinite scroll)
   */
  const loadMore = async (): Promise<void> => {
    if (!pagination.hasMore.value || loading.value) return;

    pagination.nextPage();
    const params: Record<string, any> = {
      page: pagination.currentPage.value,
      per_page: pagination.perPage.value,
      ...filters.value,
    };

    try {
      const response = await apiService.getBlogPosts(params);
      if ((response.data as any)?.posts) {
        const newPosts = (response.data as any).posts.data || [];
        if (data.value) {
          data.value.posts.push(...newPosts);
        }
        pagination.updateFromResponse({ data: (response.data as any).posts });
      }
    } catch (err) {
      console.error('Failed to load more posts:', err);
      pagination.prevPage(); // Revert page increment on error
    }
  };

  return {
    posts: computed(() => data.value?.posts || []) as ComputedRef<BlogPost[]>,
    categories: computed(() => data.value?.categories || []) as ComputedRef<string[]>,
    tags: computed(() => data.value?.tags || []) as ComputedRef<string[]>,
    filters,
    loading,
    error,
    pagination,
    fetchPosts,
    refresh,
    applyFilters,
    loadMore,
  };
}

