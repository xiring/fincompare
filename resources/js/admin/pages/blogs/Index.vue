<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Blogs</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage blog posts</p>
      </div>
      <router-link
        to="/admin/blogs/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New Blog Post
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <FormInput
          id="q"
          v-model="filters.q"
          placeholder="Search by title"
          dense
        />
        <FormSelect
          id="status"
          v-model="filters.status"
          :options="statusOptions"
          :placeholder="false"
          dense
        />
        <PerPageSelector v-model="filters.per_page" />
        <button
          type="submit"
          class="px-4 py-2 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
        >
          Apply
        </button>
        <button
          v-if="hasFilters"
          type="button"
          @click="resetFilters"
          class="px-4 py-2 bg-charcoal-100 text-charcoal-700 rounded-lg font-medium text-sm hover:bg-charcoal-200 transition-colors"
        >
          Reset
        </button>
      </form>
    </div>

    <!-- Pagination (Above Table) -->
    <Pagination :pagination="pagination" @page-change="loadPage" class="mb-4" />

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-charcoal-200">
          <thead class="bg-charcoal-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('id')" class="flex items-center gap-1 hover:text-primary-500">
                  ID
                  <component :is="sortField.value === 'id' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'id' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Image</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Title</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Category</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Date</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-12 w-12 bg-charcoal-200"></div></td>
              <td class="px-6 py-4"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="blogs.length === 0" class="text-center">
              <td colspan="7" class="px-6 py-12 text-charcoal-500">No blog posts found</td>
            </tr>
            <tr v-else v-for="blog in blogs" :key="blog.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ blog.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <img
                  v-if="blog.featured_image"
                  :src="`/storage/${blog.featured_image}`"
                  :alt="blog.title"
                  loading="lazy"
                  class="h-12 w-12 object-cover rounded-lg border border-charcoal-200"
                />
                <div v-else class="h-12 w-12 rounded-lg border border-charcoal-200">
                  <svg class="h-6 w-6 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-charcoal-800">{{ blog.title }}</div>
                <div class="text-xs text-charcoal-500">{{ blog.slug }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ blog.category || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-yellow-100 text-yellow-800': blog.status === 'draft',
                    'bg-green-100 text-green-800': blog.status === 'published',
                    'bg-charcoal-100 text-charcoal-800': blog.status === 'archived'
                  }"
                >
                  {{ blog.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ blog.created_at ? new Date(blog.created_at).toLocaleDateString() : '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/blogs/${blog.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(blog)"
                    title="Delete"
                    class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50"
                  >
                    <DeleteIcon />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination (Below Table) -->
    <Pagination :pagination="pagination" @page-change="loadPage" />
  </div>
</template>

<script setup lang="ts">
import { reactive, computed, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useBlogsStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import FormInput from '../../components/FormInput.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';
import { debounceRouteUpdate } from '../../utils/routeDebounce';
import { debounce } from '../../utils/debounce';
import FormSelect from '../../components/FormSelect.vue';
import { ConstantOptions } from '../../constants/ConstantOptions';
import type { BlogPost } from '../../types/index';

const router = useRouter();
const route = useRoute();
const blogsStore = useBlogsStore();

// Reactive state from store
const blogs = computed(() => blogsStore.items);
const loading = computed(() => blogsStore.loading);
const pagination = computed(() => blogsStore.pagination);
const statusOptions = ConstantOptions.blogStatuses();

const sortField = reactive<{ value: string }>({ value: (route.query.sort as string) || 'id' });
const sortDir = reactive<{ value: 'asc' | 'desc' }>({ value: (route.query.dir as 'asc' | 'desc') || 'desc' });

// Initialize filters from URL query params
const filters = reactive<{ q: string; per_page: number; status: string }>({
  q: (route.query.q as string) || '',
  per_page: parseInt((route.query.per_page as string) || '5') || 5,
  status: (route.query.status as string) || '',
});

const hasFilters = computed(() => {
  return filters.q || filters.per_page !== 5 || filters.status || sortField.value !== 'id' || sortDir.value !== 'desc';
});

// Update URL query parameters with debouncing
const updateQueryParams = (page: number = 1): void => {
  const query: Record<string, any> = {
    ...route.query,
    page: page > 1 ? page.toString() : undefined,
    q: filters.q || undefined,
    per_page: filters.per_page !== 5 ? filters.per_page.toString() : undefined,
    status: filters.status || undefined,
    sort: sortField.value,
    dir: sortDir.value,
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

// Debounced fetch function to prevent rapid API calls
const debouncedFetchBlogs = debounce((page: number) => {
  fetchBlogs(page);
}, 300);

// Watch for per_page changes and automatically fetch
watch(
  () => filters.per_page,
  () => {
    updateQueryParams(1);
    debouncedFetchBlogs(1);
  }
);

const fetchBlogs = async (page: number = 1): Promise<void> => {
  try {
    const params: Record<string, any> = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      status: filters.status,
      sort: sortField.value,
      dir: sortDir.value,
    };
    await blogsStore.fetchItems(params);
  } catch (error: any) {
    console.error('Error fetching blogs:', error);
    if (error.response?.status === 401) {
      window.location.href = '/login';
    }
  }
};

const applyFilters = (): void => {
  updateQueryParams(1);
  debouncedFetchBlogs(1);
};

const resetFilters = (): void => {
  filters.q = '';
  filters.per_page = 5;
  filters.status = '';
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  debouncedFetchBlogs(1);
};

const sortBy = (field: string): void => {
  if (sortField.value === field) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDir.value = 'asc';
  }
  const currentPage = pagination.value?.current_page || 1;
  updateQueryParams(currentPage);
  debouncedFetchBlogs(currentPage);
};

const loadPage = (page: number): void => {
  updateQueryParams(page);
  debouncedFetchBlogs(page);
};

const handleDelete = async (blog: BlogPost): Promise<void> => {
  if (!confirm(`Delete blog post "${blog.title}"?`)) return;

  try {
    await blogsStore.deleteItem(blog.id);
    // Store automatically updates the list, but we may need to refresh if pagination changed
    if (blogs.value.length === 0 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      fetchBlogs(newPage);
    }
  } catch (error: any) {
    console.error('Error deleting blog:', error);
    alert('Failed to delete blog post');
  }
};

onMounted(() => {
  // Initialize from URL query params
  const page = parseInt((route.query.page as string) || '1') || 1;
  sortField.value = (route.query.sort as string) || 'id';
  sortDir.value = (route.query.dir as 'asc' | 'desc') || 'desc';

  fetchBlogs(page);
});
</script>
