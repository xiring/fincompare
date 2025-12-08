<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">CMS Pages</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage CMS pages</p>
      </div>
      <router-link
        to="/admin/cms-pages/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New CMS Page
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by title"
          class="min-w-[200px] px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
        />
        <select
          v-model="filters.status"
          class="px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
        >
          <option value="">All Statuses</option>
          <option value="draft">Draft</option>
          <option value="published">Published</option>
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
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Title</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Date</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="i" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-6 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="pages.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-12 text-charcoal-500">No CMS pages found</td>
            </tr>
            <tr v-else v-for="page in pages" :key="page.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ page.id }}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-charcoal-800">{{ page.title }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ page.slug }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-yellow-100 text-yellow-800': page.status === 'draft',
                    'bg-green-100 text-green-800': page.status === 'published'
                  }"
                >
                  {{ page.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">
                {{ new Date(page.created_at).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/cms-pages/${page.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(page)"
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

<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useCmsPagesStore } from '../../stores';
import { useIndexPage } from '../../composables/useIndexPage';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';

const route = useRoute();
const cmsPagesStore = useCmsPagesStore();

// Use the composable with extra filters
const {
  items: pages,
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
} = useIndexPage(cmsPagesStore, {
  extraFilters: {
    status: '',
  },
});

const handleDelete = async (page) => {
  if (!confirm(`Delete CMS page "${page.title}"?`)) return;

  try {
    await cmsPagesStore.deleteItem(page.id);
    if (pages.value.length === 0 && pagination.value.current_page > 1) {
      fetchItems(pagination.value.current_page - 1);
    }
  } catch (error) {
    console.error('Error deleting CMS page:', error);
    alert('Failed to delete CMS page');
  }
};

onMounted(() => {
  const page = parseInt(route.query.page) || 1;
  fetchItems(page);
});
</script>
