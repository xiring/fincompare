<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Forms</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage forms</p>
      </div>
      <router-link
        to="/admin/forms/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors"
      >
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Form
      </router-link>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by name"
          class="min-w-[200px] px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
        />
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Inputs</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-12"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 ml-auto"></div></td>
            </tr>
            <tr v-else-if="forms.length === 0" class="text-center">
              <td colspan="5" class="px-6 py-12 text-gray-500 dark:text-gray-400">No forms found</td>
            </tr>
            <tr v-else v-for="form in forms" :key="form.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ form.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ form.slug }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="form.status === 'active'
                    ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400'"
                >
                  {{ form.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ (form.inputs || []).length }} input(s)
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="handleDuplicate(form)"
                    title="Duplicate"
                    class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 dark:hover:bg-blue-900/20 dark:text-blue-400 rounded-lg transition-colors"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                  </button>
                  <router-link
                    :to="`/admin/forms/${form.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-600 hover:text-primary-900 hover:bg-primary-50 dark:hover:bg-primary-900/20 dark:text-primary-400 rounded-lg transition-colors"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </router-link>
                  <button
                    @click="handleDelete(form)"
                    title="Delete"
                    class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 rounded-lg transition-colors"
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
import { useFormsStore } from '../../stores';
import { adminApi } from '../../services/api';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';

const formsStore = useFormsStore();

// Reactive state from store
const forms = computed(() => formsStore.items);
const loading = computed(() => formsStore.loading);
const pagination = computed(() => formsStore.pagination);

const filters = reactive({
  q: '',
  per_page: 5
});

const hasFilters = computed(() => {
  return filters.q || filters.per_page !== 5;
});

// Watch for per_page changes and automatically fetch
watch(() => filters.per_page, () => {
  fetchForms(1);
});

const fetchForms = async (page = 1) => {
  try {
    const params = {
      page,
      per_page: filters.per_page,
      q: filters.q
    };
    await formsStore.fetchItems(params);
  } catch (error) {
    console.error('Error fetching forms:', error);
  }
};

const applyFilters = () => {
  fetchForms(1);
};

const resetFilters = () => {
  filters.q = '';
  filters.per_page = 5;
  fetchForms(1);
};

const loadPage = (page) => {
  fetchForms(page);
};

const handleDelete = async (form) => {
  if (!confirm(`Delete form "${form.name}"?`)) return;

  try {
    await formsStore.deleteItem(form.id);
    // Store automatically updates the list, but we may need to refresh if pagination changed
    if (forms.value.length === 0 && pagination.value.current_page > 1) {
      fetchForms(pagination.value.current_page - 1);
    }
  } catch (error) {
    console.error('Error deleting form:', error);
    alert('Failed to delete form');
  }
};

const handleDuplicate = async (form) => {
  if (!confirm(`Duplicate form "${form.name}"?`)) return;

  try {
    await formsStore.duplicateItem(form.id);
    // Refresh the list after duplication
    fetchForms(pagination.value?.current_page || 1);
  } catch (error) {
    console.error('Error duplicating form:', error);
    alert('Failed to duplicate form');
  }
};

onMounted(() => {
  fetchForms();
});
</script>
