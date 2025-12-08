<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-charcoal-800">Activity Log</h1>
      <p class="mt-1 text-sm text-charcoal-600">View system activity logs</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by description or causer"
          class="min-w-[200px] px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
        />
        <select
          v-model="filters.log_name"
          class="px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Time</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Log Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Description</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Causer</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Subject</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="activities.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-12 text-charcoal-500">No activity logs found</td>
            </tr>
            <tr v-else v-for="activity in activities" :key="activity.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ activity.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ new Date(activity.created_at).toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                  {{ activity.log_name }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-charcoal-800">{{ activity.description }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ activity.causer?.name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ activity.subject_type ? `${activity.subject_type} #${activity.subject_id}` : '-' }}
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
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useActivityStore } from '../../stores';
import { useIndexPage } from '../../composables/useIndexPage';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import { ArrowUpIcon, ArrowDownIcon } from '../../components/icons';
import type { ActivityLog } from '../../types/index';

const route = useRoute();
const activityStore = useActivityStore();

// Use the composable with extra filters
const {
  items: activities,
  loading,
  pagination,
  filters,
  sortField,
  sortDir,
  hasFilters,
  fetchItems,
  applyFilters,
  resetFilters,
  sortBy,
  loadPage,
} = useIndexPage<ActivityLog>(activityStore, {
  extraFilters: {
    log_name: '',
  },
});

onMounted(() => {
  const page = parseInt((route.query.page as string) || '1') || 1;
  fetchItems(page);
});
</script>
