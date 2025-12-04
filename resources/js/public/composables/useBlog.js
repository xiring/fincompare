/**
 * Blog posts composable with pagination and filtering
 */

import { ref, computed } from 'vue';
import { useAsyncData } from './useAsyncData';
import { usePagination } from './usePagination';
import { apiService } from '../services/api';
import { PAGINATION } from '../utils/constants';

export function useBlog(options = {}) {
  const { perPage = PAGINATION.BLOG_PER_PAGE, immediate = false } = options;

  const filters = ref({
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
  } = useAsyncData(
    async () => {
      const params = {
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
      if (response.data.posts) {
        pagination.updateFromResponse({ data: response.data.posts });
      }

      return {
        posts: response.data.posts?.data || [],
        categories: response.data.categories || [],
        tags: response.data.tags || [],
      };
    },
    { immediate }
  );

  /**
   * Apply filters and reset to first page
   */
  const applyFilters = (newFilters = {}) => {
    filters.value = { ...filters.value, ...newFilters };
    pagination.reset();
    return fetchPosts();
  };

  /**
   * Load more posts (infinite scroll)
   */
  const loadMore = async () => {
    if (!pagination.hasMore.value || loading.value) return;

    pagination.nextPage();
    const params = {
      page: pagination.currentPage.value,
      per_page: pagination.perPage.value,
      ...filters.value,
    };

    try {
      const response = await apiService.getBlogPosts(params);
      if (response.data.posts) {
        const newPosts = response.data.posts.data || [];
        data.value.posts.push(...newPosts);
        pagination.updateFromResponse({ data: response.data.posts });
      }
    } catch (err) {
      console.error('Failed to load more posts:', err);
      pagination.prevPage(); // Revert page increment on error
    }
  };

  return {
    posts: computed(() => data.value?.posts || []),
    categories: computed(() => data.value?.categories || []),
    tags: computed(() => data.value?.tags || []),
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

