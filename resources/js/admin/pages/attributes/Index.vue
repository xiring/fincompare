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
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Attribute
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
                  <svg class="inline h-4 w-4" :class="sortField.value === 'id' ? 'text-primary-500' : 'text-charcoal-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortField.value === 'id' && sortDir.value === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                  </svg>
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-primary-500">
                  Name
                  <svg class="inline h-4 w-4" :class="sortField.value === 'name' ? 'text-primary-500' : 'text-charcoal-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sortField.value === 'name' && sortDir.value === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                  </svg>
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
              <td colspan="5" class="px-6 py-12 text-charcoal-500">No attributes found</td>
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
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ attribute.product_category?.name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/attributes/${attribute.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </router-link>
                  <button
                    @click="handleDelete(attribute)"
                    title="Delete"
                    class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed, onMounted, watch } from 'vue';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import { useAttributesStore } from '../../stores';

const attributesStore = useAttributesStore();

// Reactive state from store
const attributes = computed(() => attributesStore.items);
const loading = computed(() => attributesStore.loading);
const pagination = computed(() => attributesStore.pagination);

const sortField = reactive({ value: 'name' });
const sortDir = reactive({ value: 'asc' });

const filters = reactive({
  q: '',
  per_page: 5
});

const hasFilters = computed(() => {
  return filters.q || filters.per_page !== 5 || sortField.value !== 'id' || sortDir.value !== 'desc';
});

// Update URL query parameters
const updateQueryParams = (page = 1) => {
  const query = {
    ...route.query,
    page: page > 1 ? page.toString() : undefined,
    q: filters.q || undefined,
    per_page: filters.per_page !== 5 ? filters.per_page.toString() : undefined,
    sort: sortField.value,
    dir: sortDir.value
  };

  // Remove undefined values
  Object.keys(query).forEach(key => {
    if (query[key] === undefined) {
      delete query[key];
    }
  });

  router.replace({ query });
};

// Watch for per_page changes and automatically fetch
watch(() => filters.per_page, () => {
  updateQueryParams(1);
  fetchAttributes(1);
});

const fetchAttributes = async (page = 1) => {
  try {
    const params = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      sort: sortField.value,
      dir: sortDir.value
    };
    await attributesStore.fetchItems(params);
  } catch (error) {
    console.error('Error fetching attributes:', error);
  }
};

const applyFilters = () => {
  updateQueryParams(1);
  fetchAttributes(1);
};

const resetFilters = () => {
  filters.q = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  fetchAttributes(1);
};

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDir.value = 'asc';
  }
  const currentPage = pagination.value?.current_page || 1;
  updateQueryParams(currentPage);
  fetchAttributes(currentPage);
};

const loadPage = (page) => {
  updateQueryParams(page);
  fetchAttributes(page);
};

const formatDataType = (dataType) => {
  const typeMap = {
    'text': 'Text',
    'number': 'Number',
    'percentage': 'Percentage',
    'boolean': 'Boolean',
    'json': 'JSON'
  };
  return typeMap[dataType] || dataType;
};

const handleDelete = async (attribute) => {
  if (!confirm(`Delete attribute "${attribute.name}"?`)) return;

  try {
    await attributesStore.deleteItem(attribute.id);
    // Store automatically updates the list, but we may need to refresh if pagination changed
    if (attributes.value.length === 0 && pagination.value.current_page > 1) {
      const newPage = pagination.value.current_page - 1;
      updateQueryParams(newPage);
      fetchAttributes(newPage);
    }
  } catch (error) {
    console.error('Error deleting attribute:', error);
    alert('Failed to delete attribute');
  }
};

onMounted(() => {
  // Initialize from URL query params
  const page = parseInt(route.query.page) || 1;
  sortField.value = route.query.sort || 'id';
  sortDir.value = route.query.dir || 'desc';

  fetchAttributes(page);
});
</script>
