<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Forms</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage forms</p>
      </div>
      <router-link
        to="/admin/forms/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New Form
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by name"
          class="min-w-[200px] px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Inputs</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="forms.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-12 text-charcoal-500">No forms found</td>
            </tr>
            <tr v-else v-for="form in forms" :key="form.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ form.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-800">{{ form.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ form.slug }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="form.status
                    ? 'bg-green-100 text-green-800'
                    : 'bg-charcoal-100 text-charcoal-800'"
                >
                  {{ form.status ? 'active' : 'inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ (form.inputs || []).length }} input(s)
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="handleDuplicate(form)"
                    title="Duplicate"
                    class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                  </button>
                  <router-link
                    :to="`/admin/forms/${form.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(form)"
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
import { useFormsStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';
import { debounceRouteUpdate } from '../../utils/routeDebounce';
import { debounce } from '../../utils/debounce';
import type { Form } from '../../types/index';

const router = useRouter();
const route = useRoute();
const formsStore = useFormsStore();

// Reactive state from store
const forms = computed(() => formsStore.items);
const loading = computed(() => formsStore.loading);
const pagination = computed(() => formsStore.pagination);

const sortField = reactive<{ value: string }>({ value: (route.query.sort as string) || 'id' });
const sortDir = reactive<{ value: 'asc' | 'desc' }>({ value: (route.query.dir as 'asc' | 'desc') || 'desc' });

// Initialize filters from URL query params
const filters = reactive<{ q: string; per_page: number }>({
  q: (route.query.q as string) || '',
  per_page: parseInt((route.query.per_page as string) || '5') || 5,
});

const hasFilters = computed(() => {
  return filters.q || filters.per_page !== 5 || sortField.value !== 'id' || sortDir.value !== 'desc';
});

// Update URL query parameters with debouncing
const updateQueryParams = (page: number = 1): void => {
  const query: Record<string, any> = {
    ...route.query,
    page: page > 1 ? page.toString() : undefined,
    q: filters.q || undefined,
    per_page: filters.per_page !== 5 ? filters.per_page.toString() : undefined,
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
const debouncedFetchForms = debounce((page: number) => {
  fetchForms(page);
}, 300);

// Watch for per_page changes and automatically fetch
watch(
  () => filters.per_page,
  () => {
    updateQueryParams(1);
    debouncedFetchForms(1);
  }
);

const fetchForms = async (page: number = 1): Promise<void> => {
  try {
    const params: Record<string, any> = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      sort: sortField.value,
      dir: sortDir.value,
    };
    await formsStore.fetchItems(params);
  } catch (error: any) {
    console.error('Error fetching forms:', error);
    if (error.response?.status === 401) {
      window.location.href = '/login';
    }
  }
};

const applyFilters = (): void => {
  updateQueryParams(1);
  debouncedFetchForms(1);
};

const resetFilters = (): void => {
  filters.q = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  debouncedFetchForms(1);
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
  debouncedFetchForms(currentPage);
};

const loadPage = (page: number): void => {
  updateQueryParams(page);
  debouncedFetchForms(page);
};

const handleDelete = async (form: Form): Promise<void> => {
  if (!confirm(`Delete form "${form.name}"?`)) return;

  try {
    await formsStore.deleteItem(form.id);
    // Store automatically updates the list, but we may need to refresh if pagination changed
    if (forms.value.length === 0 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      fetchForms(newPage);
    }
  } catch (error: any) {
    console.error('Error deleting form:', error);
    alert('Failed to delete form');
  }
};

const handleDuplicate = async (form: Form): Promise<void> => {
  if (!confirm(`Duplicate form "${form.name}"?`)) return;

  try {
    await (formsStore as any).duplicateItem(form.id);
    const currentPage = pagination.value?.current_page || 1;
    fetchForms(currentPage);
  } catch (error: any) {
    console.error('Error duplicating form:', error);
    alert('Failed to duplicate form');
  }
};

onMounted(() => {
  // Initialize from URL query params
  const page = parseInt((route.query.page as string) || '1') || 1;
  sortField.value = (route.query.sort as string) || 'id';
  sortDir.value = (route.query.dir as 'asc' | 'desc') || 'desc';

  fetchForms(page);
});
</script>
