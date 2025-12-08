<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Activity Log</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View system activity logs</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by description or causer"
          class="min-w-[200px] px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
        />
        <select
          v-model="filters.log_name"
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">All Logs</option>
          <option value="products">Products</option>
          <option value="partners">Partners</option>
          <option value="users">Users</option>
          <option value="leads">Leads</option>
          <option value="forms">Forms</option>
          <option value="blogs">Blogs</option>
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Time</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Log Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Causer</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Subject</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div></td>
              <td class="px-6 py-4"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-48"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
            </tr>
            <tr v-else-if="activities.length === 0" class="text-center">
              <td colspan="5" class="px-6 py-12 text-gray-500 dark:text-gray-400">No activity logs found</td>
            </tr>
            <tr v-else v-for="activity in activities" :key="activity.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ new Date(activity.created_at).toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                  {{ activity.log_name }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ activity.description }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ activity.causer?.name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ activity.subject_type ? `${activity.subject_type} #${activity.subject_id}` : '-' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { watch, reactive, computed, onMounted } from 'vue';
import { useActivityStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';

const activityStore = useActivityStore();

// Reactive state from store
const activities = computed(() => activityStore.items);
const loading = computed(() => activityStore.loading);
const pagination = computed(() => activityStore.pagination);

const filters = reactive({
  q: '',
  log_name: '',
  per_page: 5
});

const hasFilters = computed(() => {
  return filters.q || filters.log_name || filters.per_page !== 5;
});

// Watch for per_page changes and automatically fetch
watch(() => filters.per_page, () => {
  fetchActivities(1);
});

const fetchActivities = async (page = 1) => {
  try {
    const params = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      log_name: filters.log_name
    };
    await activityStore.fetchItems(params);
  } catch (error) {
    console.error('Error fetching activities:', error);
  }
};

const applyFilters = () => {
  fetchActivities(1);
};

const resetFilters = () => {
  filters.q = '';
  filters.log_name = '';
  filters.per_page = 5;
  fetchActivities(1);
};

const loadPage = (page) => {
  fetchActivities(page);
};

onMounted(() => {
  fetchActivities();
});
</script>
