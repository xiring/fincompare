<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Products</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage product catalog</p>
      </div>
      <div class="flex items-center gap-3">
        <router-link
          to="/admin/products/import"
          class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-600 text-white rounded-lg font-medium text-sm hover:bg-amber-700 transition-colors"
        >
          <UploadIcon class="h-5 w-5 mr-2" />
          Import
        </router-link>
        <router-link
          to="/admin/products/create"
          class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-500 transition-colors"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          New Product
        </router-link>
      </div>
    </div>

    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-4" />
    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-4" />

    <!-- Filters -->
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
        <FormSelect
          id="partner_id"
          v-model="filters.partner_id"
          :options="partnerOptions"
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

    <!-- Products Table -->
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-primary-500">
                  Name
                  <component :is="sortField.value === 'name' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'name' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Category</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Partner</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('status')" class="flex items-center gap-1 hover:text-primary-500">
                  Status
                  <component :is="sortField.value === 'status' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'status' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="`skeleton-${i}`" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-12 w-12 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="products.length === 0" class="text-center">
              <td colspan="7" class="px-6 py-12 text-charcoal-500">
                <EmptyState title="No products found" description="Try adjusting filters or create a new product.">
                  <template #action>
                    <router-link
                      to="/admin/products/create"
                      class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
                    >
                      <PlusIcon class="h-5 w-5 mr-2" />
                      New Product
                    </router-link>
                  </template>
                </EmptyState>
              </td>
            </tr>
            <tr v-else v-for="product in products" :key="product.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ product.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <img
                  v-if="product.image"
                  :src="`/storage/${product.image}`"
                  :alt="product.name"
                  loading="lazy"
                  class="h-12 w-12 object-cover rounded-lg border border-charcoal-200"
                />
                <div v-else class="h-12 w-12 rounded-lg border border-charcoal-200">
                  <svg class="h-6 w-6 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-800">{{ product.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ product.product_category?.name || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ product.partner?.name || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <StatusBadge :status="product.status ?? (product.status === false ? 'inactive' : 'active')" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="handleDuplicate(product)"
                    title="Duplicate"
                    class="inline-flex items-center justify-center p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 disabled:opacity-50"
                    :disabled="duplicatingId === product.id || duplicateMutation.isPending.value"
                  >
                    <CopyIcon class="h-5 w-5" />
                  </button>
                  <router-link
                    :to="`/admin/products/${product.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(product)"
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
  </div>
  <ConfirmModal
    v-model="showConfirm"
    title="Delete product"
    :message="confirmMessage"
    @confirm="confirmDelete"
  />
</template>

<script setup lang="ts">
import { reactive, computed, onMounted, watch, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormInput from '../../components/FormInput.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import EmptyState from '../../components/EmptyState.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import { UploadIcon, PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon, CopyIcon } from '../../components/icons';
import { debounceRouteUpdate } from '../../utils/routeDebounce';
import { debounce } from '../../utils/debounce';
import { useProductListQuery, useProductDeleteMutation, useProductDuplicateMutation } from '../../queries/products';
import { useProductCategoryListQuery } from '../../queries/productCategories';
import { usePartnerListQuery } from '../../queries/partners';
import type { Product } from '../../types/index';

const router = useRouter();
const route = useRoute();
const currentPage = ref<number>(parseInt((route.query.page as string) || '1') || 1);

const sortField = reactive<{ value: string }>({ value: (route.query.sort as string) || 'id' });
const sortDir = reactive<{ value: 'asc' | 'desc' }>({ value: (route.query.dir as 'asc' | 'desc') || 'desc' });

const filters = reactive<{ q: string; product_category_id: string; partner_id: string; per_page: number }>({
  q: (route.query.q as string) || '',
  product_category_id: (route.query.product_category_id as string) || '',
  partner_id: (route.query.partner_id as string) || '',
  per_page: parseInt((route.query.per_page as string) || '5') || 5,
});

const hasFilters = computed(
  () =>
    filters.q ||
    filters.product_category_id ||
    filters.partner_id ||
    filters.per_page !== 5 ||
    sortField.value !== 'id' ||
    sortDir.value !== 'desc'
);

const updateQueryParams = (page: number = 1): void => {
  const query: Record<string, any> = {
    ...route.query,
    page: page > 1 ? page.toString() : undefined,
    q: filters.q || undefined,
    product_category_id: filters.product_category_id || undefined,
    partner_id: filters.partner_id || undefined,
    per_page: filters.per_page !== 5 ? filters.per_page.toString() : undefined,
    sort: sortField.value,
    dir: sortDir.value,
  };

  Object.keys(query).forEach((key) => {
    if (query[key] === undefined) {
      delete query[key];
    }
  });

  debounceRouteUpdate(router, query);
};

const debouncedSetPage = debounce((page: number) => {
  currentPage.value = page;
}, 300);

watch(
  () => filters.per_page,
  () => {
    updateQueryParams(1);
    debouncedSetPage(1);
  }
);

const listParams = computed(() => ({
  page: currentPage.value,
  per_page: filters.per_page,
  q: filters.q || undefined,
  product_category_id: filters.product_category_id || undefined,
  partner_id: filters.partner_id || undefined,
  sort: sortField.value,
  dir: sortDir.value,
}));

const { data, isLoading, isFetching } = useProductListQuery(listParams);
const deleteMutation = useProductDeleteMutation();
const duplicateMutation = useProductDuplicateMutation();
const products = computed(() => data.value?.items || []);
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
const loading = computed(() => isLoading.value || isFetching.value);

const { data: categoriesData } = useProductCategoryListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const { data: partnersData } = usePartnerListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const categoryOptions = computed(() => [
  { id: '', name: 'All categories' },
  ...((categoriesData?.value?.items || []) as any[]).map((c: any) => ({
    id: c.id,
    name: c.name,
  })),
]);
const partnerOptions = computed(() => [
  { id: '', name: 'All partners' },
  ...((partnersData?.value?.items || []) as any[]).map((p: any) => ({
    id: p.id,
    name: p.name,
  })),
]);

const applyFilters = (): void => {
  updateQueryParams(1);
  debouncedSetPage(1);
};

const resetFilters = (): void => {
  filters.q = '';
  filters.product_category_id = '';
  filters.partner_id = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  debouncedSetPage(1);
};

const sortBy = (field: string): void => {
  if (sortField.value === field) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDir.value = 'asc';
  }
  const page = pagination.value?.current_page || 1;
  updateQueryParams(page);
  debouncedSetPage(page);
};

const loadPage = (page: number): void => {
  updateQueryParams(page);
  debouncedSetPage(page);
};

const showConfirm = ref(false);
const pendingDelete = ref<Product | null>(null);
const duplicatingId = ref<number | string | null>(null);
const successMessage = ref('');
const errorMessage = ref('');

const handleDelete = async (product: Product): Promise<void> => {
  pendingDelete.value = product;
  showConfirm.value = true;
};

const handleDuplicate = async (product: Product): Promise<void> => {
  try {
    duplicatingId.value = product.id;
    successMessage.value = '';
    errorMessage.value = '';
    await duplicateMutation.mutateAsync(product.id);
    successMessage.value = 'Product duplicated successfully.';
  } catch (error: any) {
    console.error('Error duplicating product:', error);
    errorMessage.value =
      error?.response?.data?.message ||
      error?.message ||
      'Failed to duplicate product. Please try again or check permissions.';
  } finally {
    duplicatingId.value = null;
  }
};

const confirmDelete = async (): Promise<void> => {
  if (!pendingDelete.value) return;
  const product = pendingDelete.value;
  try {
    await deleteMutation.mutateAsync(product.id);
    if (products.value.length <= 1 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      debouncedSetPage(newPage);
    }
  } catch (error: any) {
    console.error('Error deleting product:', error);
    alert('Failed to delete product');
  } finally {
    pendingDelete.value = null;
    showConfirm.value = false;
  }
};

const confirmMessage = computed(() =>
  pendingDelete.value ? `Delete product "${pendingDelete.value.name}"? This cannot be undone.` : ''
);

onMounted(() => {
  const page = parseInt((route.query.page as string) || '1') || 1;
  sortField.value = (route.query.sort as string) || 'id';
  sortDir.value = (route.query.dir as 'asc' | 'desc') || 'desc';
  currentPage.value = page;
});
</script>

