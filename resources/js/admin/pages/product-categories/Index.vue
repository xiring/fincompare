<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Product Categories</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage product categories</p>
      </div>
      <router-link
        to="/admin/product-categories/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New Category
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <FormInput
          id="q"
          v-model="filters.q"
          placeholder="Search by name"
          dense
        />
        <GroupFilterSelect v-model="filters.group_id" />
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-primary-500">
                  Name
                  <component :is="sortField.value === 'name' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'name' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Group</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Description</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="categories.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-12 text-charcoal-500">No categories found</td>
            </tr>
            <tr v-else v-for="category in categories" :key="category.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ category.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-800">{{ category.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ category.slug }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                <GroupBadge :name="(category as any).group?.name" />
              </td>
              <td class="px-6 py-4 text-sm text-charcoal-600">{{ category.description || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/product-categories/${category.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(category)"
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

    <ConfirmModal
      v-model="showConfirm"
      title="Delete category"
      :message="confirmMessage"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { reactive, computed, onMounted, watch, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useProductCategoriesStore, useGroupsStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';
import GroupFilterSelect from '../../components/GroupFilterSelect.vue';
import GroupBadge from '../../components/GroupBadge.vue';
import FormInput from '../../components/FormInput.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import { debounceRouteUpdate } from '../../utils/routeDebounce';
import { debounce } from '../../utils/debounce';
import type { ProductCategory } from '../../types/index';

const router = useRouter();
const route = useRoute();
const productCategoriesStore = useProductCategoriesStore();
const groupsStore = useGroupsStore();

// Reactive state from store
const categories = computed(() => productCategoriesStore.items);
const loading = computed(() => productCategoriesStore.loading);
const pagination = computed(() => productCategoriesStore.pagination);
const sortField = reactive<{ value: string }>({ value: (route.query.sort as string) || 'id' });
const sortDir = reactive<{ value: 'asc' | 'desc' }>({ value: (route.query.dir as 'asc' | 'desc') || 'desc' });

// Initialize filters from URL query params
const filters = reactive<{ q: string; group_id: string; per_page: number }>({
  q: (route.query.q as string) || '',
  group_id: (route.query.group_id as string) || '',
  per_page: parseInt((route.query.per_page as string) || '5') || 5,
});
const showConfirm = ref(false);
const pendingDelete = ref<ProductCategory | null>(null);

const hasFilters = computed(() => {
  return filters.q || filters.group_id || filters.per_page !== 5 || sortField.value !== 'id' || sortDir.value !== 'desc';
});

// Update URL query parameters with debouncing
const updateQueryParams = (page: number = 1): void => {
  const query: Record<string, any> = {
    ...route.query,
    page: page > 1 ? page.toString() : undefined,
    q: filters.q || undefined,
    group_id: filters.group_id || undefined,
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
const debouncedFetchCategories = debounce((page: number) => {
  fetchCategories(page);
}, 300);

// Watch for per_page changes and automatically fetch
watch(
  () => filters.per_page,
  () => {
    updateQueryParams(1);
    debouncedFetchCategories(1);
  }
);

const fetchCategories = async (page: number = 1): Promise<void> => {
  try {
    const params: Record<string, any> = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      group_id: filters.group_id || undefined,
      sort: sortField.value,
      dir: sortDir.value,
    };
    await productCategoriesStore.fetchItems(params);
  } catch (error: any) {
    console.error('Error fetching categories:', error);
    if (error.response?.status === 401) {
      window.location.href = '/login';
    }
  }
};

const applyFilters = (): void => {
  updateQueryParams(1);
  debouncedFetchCategories(1);
};

const resetFilters = (): void => {
  filters.q = '';
  filters.group_id = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  debouncedFetchCategories(1);
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
  debouncedFetchCategories(currentPage);
};

const loadPage = (page: number): void => {
  updateQueryParams(page);
  debouncedFetchCategories(page);
};

const handleDelete = (category: ProductCategory): void => {
  pendingDelete.value = category;
  showConfirm.value = true;
};

const confirmDelete = async (): Promise<void> => {
  if (!pendingDelete.value) return;
  const category = pendingDelete.value;
  try {
    await productCategoriesStore.deleteItem(category.id);
    if (categories.value.length === 0 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      fetchCategories(newPage);
    }
  } catch (error: any) {
    console.error('Error deleting category:', error);
    alert('Failed to delete category');
  } finally {
    showConfirm.value = false;
    pendingDelete.value = null;
  }
};

const confirmMessage = computed(() =>
  pendingDelete.value ? `Delete category "${pendingDelete.value.name}"? This cannot be undone.` : ''
);

onMounted(() => {
  groupsStore.fetchItems({ per_page: 1000, sort: 'name', dir: 'asc' }).catch(() => {});
  // Initialize from URL query params
  const page = parseInt((route.query.page as string) || '1') || 1;
  sortField.value = (route.query.sort as string) || 'id';
  sortDir.value = (route.query.dir as 'asc' | 'desc') || 'desc';

  fetchCategories(page);
});
</script>
