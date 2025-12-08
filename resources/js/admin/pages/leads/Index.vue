<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leads</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage leads</p>
      </div>
      <button
        @click="exportLeads"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white rounded-lg font-medium text-sm hover:bg-green-700 transition-colors"
      >
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export CSV
      </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by name or email"
          class="min-w-[200px] px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
        />
        <select
          v-model="filters.status"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">All Statuses</option>
          <option value="new">New</option>
          <option value="contacted">Contacted</option>
          <option value="qualified">Qualified</option>
          <option value="converted">Converted</option>
          <option value="rejected">Rejected</option>
        </select>
        <PerPageSelector v-model="filters.per_page" />
        <button
          type="submit"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors"
        >
          Apply
        </button>
        <button
          v-if="hasFilters"
          type="button"
          @click="resetFilters"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
        >
          Reset
        </button>
      </form>
    </div>

    <!-- Pagination (Above Table) -->
    <Pagination :pagination="pagination" @page-change="loadPage" class="mb-4" />

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Contact</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Product/Category</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-40"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-20"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 ml-auto"></div></td>
            </tr>
            <tr v-else-if="leads.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-12 text-gray-500 dark:text-gray-400">No leads found</td>
            </tr>
            <tr v-else v-for="lead in leads" :key="lead.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ lead.full_name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                <div v-if="lead.email" class="text-primary-600 dark:text-primary-400">{{ lead.email }}</div>
                <div v-if="lead.mobile_number" class="text-gray-500 dark:text-gray-400">{{ lead.mobile_number }}</div>
                <span v-if="!lead.email && !lead.mobile_number" class="text-gray-400">-</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                <div v-if="lead.product">{{ lead.product.name }}</div>
                <div v-else-if="lead.product_category">{{ lead.product_category.name }}</div>
                <span v-else class="text-gray-400">-</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400': lead.status === 'new',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400': lead.status === 'contacted',
                    'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400': lead.status === 'qualified',
                    'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400': lead.status === 'converted',
                    'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400': lead.status === 'rejected',
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400': !['new', 'contacted', 'qualified', 'converted', 'rejected'].includes(lead.status)
                  }"
                >
                  {{ lead.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ new Date(lead.created_at).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/leads/${lead.id}`"
                    title="View"
                    class="inline-flex items-center justify-center p-2 text-primary-600 hover:text-primary-900 hover:bg-primary-50 dark:hover:bg-primary-900/20 dark:text-primary-400 rounded-lg transition-colors"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </router-link>
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
import { useLeadsStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';

const leadsStore = useLeadsStore();

// Reactive state from store
const leads = computed(() => leadsStore.items);
const loading = computed(() => leadsStore.loading);
const pagination = computed(() => leadsStore.pagination);

const filters = reactive({
  q: '',
  status: '',
  per_page: 5
});

const hasFilters = computed(() => {
  return filters.q || filters.status || filters.per_page !== 5;
});

// Watch for per_page changes and automatically fetch
watch(() => filters.per_page, () => {
  fetchLeads(1);
});

const fetchLeads = async (page = 1) => {
  try {
    const params = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      status: filters.status
    };
    await leadsStore.fetchItems(params);
  } catch (error) {
    console.error('Error fetching leads:', error);
  }
};

const applyFilters = () => {
  fetchLeads(1);
};

const resetFilters = () => {
  filters.q = '';
  filters.status = '';
  filters.per_page = 5;
  fetchLeads(1);
};

const loadPage = (page) => {
  fetchLeads(page);
};

const exportLeads = async () => {
  try {
    await leadsStore.exportLeads();
  } catch (error) {
    console.error('Error exporting leads:', error);
    alert('Failed to export leads');
  }
};

onMounted(() => {
  fetchLeads();
});
</script>
