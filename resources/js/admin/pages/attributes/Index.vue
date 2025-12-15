<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Attributes</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage product attributes</p>
      </div>
      <router-link
        to="/admin/attributes/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New Attribute
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
        <FormSelect
          id="product_category_id"
          v-model="filters.product_category_id"
          :options="categoryOptions"
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-primary-500">
                  Name
                  <component :is="sortField.value === 'name' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'name' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Type</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Category</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="attributes.length === 0" class="text-center">
              <td colspan="5" class="px-6 py-12 text-charcoal-500">
                <EmptyState title="No attributes found" description="Try adjusting filters or create a new attribute.">
                  <template #action>
                    <router-link
                      to="/admin/attributes/create"
                      class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
                    >
                      <PlusIcon class="h-5 w-5 mr-2" />
                      New Attribute
                    </router-link>
                  </template>
                </EmptyState>
              </td>
            </tr>
            <tr v-else v-for="attribute in attributes" :key="attribute.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ attribute.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-800">{{ attribute.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                <span v-if="attribute.data_type" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                  {{ formatDataType(attribute.data_type) }}
                </span>
                <span v-else class="text-charcoal-400">-</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600 flex items-center gap-2">
                <span>{{ attribute.product_category?.name || '-' }}</span>
                <GroupBadge v-if="attribute.product_category?.group" :name="(attribute.product_category as any).group?.name" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/attributes/${attribute.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(attribute)"
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
      title="Delete attribute"
      :message="confirmMessage"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { reactive, computed, watch, ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormInput from '../../components/FormInput.vue';
import GroupBadge from '../../components/GroupBadge.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import EmptyState from '../../components/EmptyState.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';
import { debounceRouteUpdate } from '../../utils/routeDebounce';
import { debounce } from '../../utils/debounce';
import { useAttributeListQuery, useAttributeDeleteMutation } from '../../queries/attributes';
import { useProductCategoryListQuery } from '../../queries/productCategories';
import type { Attribute } from '../../types/index';

const router = useRouter();
const route = useRoute();
const {
  data: categoriesData,
  isLoading: categoriesLoading,
  isFetching: categoriesFetching,
} = useProductCategoryListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const categoryOptions = computed(() => [
  { id: '', name: 'All categories' },
  ...(((categoriesData?.value as any)?.items || []) as any[]).map((c: any) => ({ id: c.id, name: c.name, group: c.group })),
]);

const sortField = reactive<{ value: string }>({ value: (route.query.sort as string) || 'id' });
const sortDir = reactive<{ value: 'asc' | 'desc' }>({ value: (route.query.dir as 'asc' | 'desc') || 'desc' });

// Initialize filters from URL query params
const filters = reactive<{ q: string; group_id: string; product_category_id: string; per_page: number }>({
  q: (route.query.q as string) || '',
  group_id: (route.query.group_id as string) || '',
  product_category_id: (route.query.product_category_id as string) || '',
  per_page: parseInt((route.query.per_page as string) || '5') || 5,
});
const currentPage = ref<number>(parseInt((route.query.page as string) || '1') || 1);

const hasFilters = computed(() => {
  return (
    filters.q ||
    filters.product_category_id ||
    filters.per_page !== 5 ||
    sortField.value !== 'id' ||
    sortDir.value !== 'desc'
  );
});

// Update URL query parameters with debouncing
const updateQueryParams = (page: number = 1): void => {
  const query: Record<string, any> = {
    ...route.query,
    page: page > 1 ? page.toString() : undefined,
    q: filters.q || undefined,
    product_category_id: filters.product_category_id || undefined,
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
const debouncedFetchAttributes = debounce((page: number) => {
  currentPage.value = page;
}, 300);

// Watch for per_page changes and automatically fetch
watch(
  () => filters.per_page,
  () => {
    updateQueryParams(1);
    debouncedFetchAttributes(1);
  }
);

const applyFilters = (): void => {
  updateQueryParams(1);
  debouncedFetchAttributes(1);
};

const resetFilters = (): void => {
  filters.q = '';
  filters.product_category_id = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  debouncedFetchAttributes(1);
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
  debouncedFetchAttributes(currentPage);
};

const loadPage = (page: number): void => {
  updateQueryParams(page);
  debouncedFetchAttributes(page);
};

const formatDataType = (dataType: string): string => {
  const typeMap: Record<string, string> = {
    text: 'Text',
    number: 'Number',
    percentage: 'Percentage',
    boolean: 'Boolean',
    json: 'JSON',
  };
  return typeMap[dataType] || dataType;
};

const showConfirm = ref(false);
const pendingDelete = ref<Attribute | null>(null);
const confirmMessage = computed(() =>
  pendingDelete.value ? `Delete attribute "${pendingDelete.value.name}"? This cannot be undone.` : ''
);

const handleDelete = (attribute: Attribute): void => {
  pendingDelete.value = attribute;
  showConfirm.value = true;
};

const confirmDelete = async (): Promise<void> => {
  if (!pendingDelete.value) return;

  try {
    await deleteMutation.mutateAsync(pendingDelete.value.id);
    if (attributes.value.length <= 1 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      debouncedFetchAttributes(newPage);
    }
  } catch (error: any) {
    console.error('Error deleting attribute:', error);
    alert('Failed to delete attribute');
  } finally {
    pendingDelete.value = null;
    showConfirm.value = false;
  }
};

onMounted(() => {
  const page = parseInt((route.query.page as string) || '1') || 1;
  sortField.value = (route.query.sort as string) || 'id';
  sortDir.value = (route.query.dir as 'asc' | 'desc') || 'desc';
  currentPage.value = page;
});

const listParams = computed(() => ({
  page: currentPage.value,
  per_page: filters.per_page,
  q: filters.q || undefined,
  product_category_id: filters.product_category_id || undefined,
  group_id: filters.group_id || undefined,
  sort: sortField.value,
  dir: sortDir.value,
}));

const { data, isLoading, isFetching } = useAttributeListQuery(listParams);
const deleteMutation = useAttributeDeleteMutation();
const attributes = computed(() => data.value?.items || []);
const pagination = computed(
  () =>
    data.value?.pagination || {
      current_page: 1,
      last_page: 1,
      per_page: filters.per_page,
      total: 0,
      from: 0,
      to: 0,
      prev_page_url: null,
      next_page_url: null,
    }
);
const loading = computed(() => isLoading.value || isFetching.value || categoriesLoading.value || categoriesFetching.value);
</script>
