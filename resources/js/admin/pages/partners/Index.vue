<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Partners</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage partner companies</p>
      </div>
      <router-link
        to="/admin/partners/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New Partner
      </router-link>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <FormInput
          id="q"
          v-model="filters.q"
          placeholder="Search by name"
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

    <!-- Partners Table -->
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Logo</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-primary-500">
                  Name
                  <component :is="sortField.value === 'name' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'name' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Email</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Website</th>
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
            <tr v-else-if="partners.length === 0" class="text-center">
              <td colspan="7" class="px-6 py-12 text-charcoal-500">No partners found</td>
            </tr>
            <tr v-else v-for="partner in partners" :key="partner.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ partner.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <img
                  v-if="partner.logo_path || partner.logo"
                  :src="`/storage/${partner.logo_path || partner.logo}`"
                  :alt="partner.name"
                  loading="lazy"
                  class="h-12 w-12 object-cover rounded-lg border border-charcoal-200"
                />
                <div v-else class="h-12 w-12 rounded-lg border border-charcoal-200">
                  <svg class="h-6 w-6 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-800">{{ partner.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ (partner as any).contact_email || '-' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                <a v-if="(partner as any).website_url || partner.website" :href="(partner as any).website_url || partner.website" target="_blank" class="text-primary-500 hover:text-primary-600">
                  {{ (partner as any).website_url || partner.website || '-' }}
                </a>
                <span v-else>-</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="partner.status === 'active'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-charcoal-100 text-charcoal-800'"
                >
                  {{ partner.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/partners/${partner.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(partner)"
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
      title="Delete partner"
      :message="confirmMessage"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { reactive, computed, onMounted, watch, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import FormInput from '../../components/FormInput.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';
import { debounceRouteUpdate } from '../../utils/routeDebounce';
import { debounce } from '../../utils/debounce';
import ConfirmModal from '../../components/ConfirmModal.vue';
import { usePartnerListQuery, usePartnerDeleteMutation } from '../../queries/partners';
import type { Partner } from '../../types/index';

const router = useRouter();
const route = useRoute();
const showConfirm = ref(false);
const pendingDelete = ref<Partner | null>(null);

const sortField = reactive<{ value: string }>({ value: (route.query.sort as string) || 'id' });
const sortDir = reactive<{ value: 'asc' | 'desc' }>({ value: (route.query.dir as 'asc' | 'desc') || 'desc' });

// Initialize filters from URL query params
const filters = reactive<{ q: string; per_page: number }>({
  q: (route.query.q as string) || '',
  per_page: parseInt((route.query.per_page as string) || '5') || 5,
});
const currentPage = ref<number>(parseInt((route.query.page as string) || '1') || 1);

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
const debouncedFetchPartners = debounce((page: number) => {
  currentPage.value = page;
}, 300);

// Watch for per_page changes and automatically fetch
watch(
  () => filters.per_page,
  () => {
    updateQueryParams(1);
    debouncedFetchPartners(1);
  }
);

const applyFilters = (): void => {
  updateQueryParams(1);
  debouncedFetchPartners(1);
};

const resetFilters = (): void => {
  filters.q = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  debouncedFetchPartners(1);
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
  debouncedFetchPartners(currentPage);
};

const loadPage = (page: number): void => {
  updateQueryParams(page);
  debouncedFetchPartners(page);
};

const handleDelete = (partner: Partner): void => {
  pendingDelete.value = partner;
  showConfirm.value = true;
};

const confirmDelete = async (): Promise<void> => {
  if (!pendingDelete.value) return;
  const partner = pendingDelete.value;
  try {
    await deleteMutation.mutateAsync(partner.id);
    if (partners.value.length <= 1 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      debouncedFetchPartners(newPage);
    }
  } catch (error: any) {
    console.error('Error deleting partner:', error);
    alert('Failed to delete partner');
  } finally {
    showConfirm.value = false;
    pendingDelete.value = null;
  }
};

const confirmMessage = computed(() =>
  pendingDelete.value ? `Delete partner "${pendingDelete.value.name}"? This cannot be undone.` : ''
);

onMounted(() => {
  // Initialize from URL query params
  const page = parseInt((route.query.page as string) || '1') || 1;
  sortField.value = (route.query.sort as string) || 'id';
  sortDir.value = (route.query.dir as 'asc' | 'desc') || 'desc';

  currentPage.value = page;
});

const listParams = computed(() => ({
  page: currentPage.value,
  per_page: filters.per_page,
  q: filters.q || undefined,
  sort: sortField.value,
  dir: sortDir.value,
}));

const { data, isLoading, isFetching } = usePartnerListQuery(listParams);
const deleteMutation = usePartnerDeleteMutation();
const partners = computed(() => data.value?.items || []);
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
</script>
