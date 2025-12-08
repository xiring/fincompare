<template>
  <div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-charcoal-800">Users</h1>
        <p class="mt-1 text-sm text-charcoal-600">Manage users</p>
      </div>
      <router-link
        to="/admin/users/create"
        class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 transition-colors"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        New User
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 mb-6">
      <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-3">
        <input
          v-model="filters.q"
          type="text"
          placeholder="Search by name or email"
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
                  <component :is="sortField.value === 'id' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'id' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">
                <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-primary-500">
                  Name
                  <component :is="sortField.value === 'name' && sortDir.value === 'asc' ? ArrowUpIcon : ArrowDownIcon" class="inline h-4 w-4" :class="sortField.value === 'name' ? 'text-primary-500' : 'text-charcoal-400'" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Email</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-charcoal-600">Roles</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-charcoal-600">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <tr v-if="loading" v-for="i in 5" :key="`skeleton-${i}`" class="animate-pulse">
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap"><div class="h-4 bg-charcoal-200"></div></td>
              <td class="px-6 py-4 whitespace-nowrap text-right"><div class="h-8 bg-charcoal-200"></div></td>
            </tr>
            <tr v-else-if="users.length === 0" class="text-center">
              <td colspan="5" class="px-6 py-12 text-charcoal-500">No users found</td>
            </tr>
            <tr v-else v-for="user in users" :key="user.id" class="hover:bg-charcoal-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ user.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-800">{{ user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-600">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-wrap gap-1">
                  <span
                    v-for="role in user.roles || []"
                    :key="role.id"
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800"
                  >
                    {{ role.name }}
                  </span>
                  <span v-if="!user.roles || user.roles.length === 0" class="text-xs text-charcoal-500">No roles</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <router-link
                    :to="`/admin/users/${user.id}/edit`"
                    title="Edit"
                    class="inline-flex items-center justify-center p-2 text-primary-500 hover:text-primary-900 hover:bg-primary-50"
                  >
                    <EditIcon />
                  </router-link>
                  <button
                    @click="handleDelete(user)"
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
</template>

<script setup>
import { reactive, computed, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useUsersStore } from '../../stores';
import Pagination from '../../components/Pagination.vue';
import PerPageSelector from '../../components/PerPageSelector.vue';
import { PlusIcon, EditIcon, DeleteIcon, ArrowUpIcon, ArrowDownIcon } from '../../components/icons';

const router = useRouter();
const route = useRoute();
const usersStore = useUsersStore();

// Reactive state from store
const users = computed(() => usersStore.items);
const loading = computed(() => usersStore.loading);
const pagination = computed(() => usersStore.pagination);

const sortField = reactive({ value: route.query.sort || 'id' });
const sortDir = reactive({ value: route.query.dir || 'desc' });

// Initialize filters from URL query params
const filters = reactive({
  q: route.query.q || '',
  per_page: parseInt(route.query.per_page) || 5
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
  fetchUsers(1);
});

const fetchUsers = async (page = 1) => {
  try {
    const params = {
      page,
      per_page: filters.per_page,
      q: filters.q,
      sort: sortField.value,
      dir: sortDir.value
    };
    await usersStore.fetchItems(params);
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

const applyFilters = () => {
  updateQueryParams(1);
  fetchUsers(1);
};

const resetFilters = () => {
  filters.q = '';
  filters.per_page = 5;
  sortField.value = 'id';
  sortDir.value = 'desc';
  router.replace({ query: {} });
  fetchUsers(1);
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
  fetchUsers(currentPage);
};

const loadPage = (page) => {
  updateQueryParams(page);
  fetchUsers(page);
};

const handleDelete = async (user) => {
  if (!confirm(`Delete user "${user.name}"?`)) return;

  try {
    await usersStore.deleteItem(user.id);
    // Store automatically updates the list, but we may need to refresh if pagination changed
    if (users.value.length === 0 && pagination.value.current_page > 1) {
      fetchUsers(pagination.value.current_page - 1);
    }
  } catch (error) {
    console.error('Error deleting user:', error);
    alert('Failed to delete user');
  }
};

onMounted(() => {
  // Initialize from URL query params
  const page = parseInt(route.query.page) || 1;
  sortField.value = route.query.sort || 'id';
  sortDir.value = route.query.dir || 'desc';

  fetchUsers(page);
});
</script>

