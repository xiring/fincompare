<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Role</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update role information</p>
    </div>

    <LoadingSpinner v-if="loading && !role" text="Loading role..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="role" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Name <span class="text-red-500">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.name }"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Permissions
          </label>
          <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3">
            <label
              v-for="permission in permissions"
              :key="permission.id"
              class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded cursor-pointer"
            >
              <input
                :id="`permission-${permission.id}`"
                v-model="form.permissions"
                type="checkbox"
                :value="permission.id"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
              />
              <span class="text-sm text-gray-700 dark:text-gray-300">{{ permission.name }}</span>
            </label>
            <p v-if="permissions.length === 0" class="text-sm text-gray-500 dark:text-gray-400">No permissions available</p>
          </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/admin/roles"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
            <span>{{ loading ? 'Updating...' : 'Update Role' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { adminApi } from '../../services/api';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const route = useRoute();
const router = useRouter();
const roleId = route.params.id;

const role = ref(null);
const form = reactive({
  name: '',
  permissions: []
});

const permissions = ref([]);
const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);

const loadRole = async () => {
  loading.value = true;
  try {
    const response = await adminApi.roles.show(roleId);
    role.value = response.data;

    form.name = role.value.name || '';
    form.permissions = (role.value.permissions || []).map(p => p.id);
  } catch (error) {
    console.error('Error loading role:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Role not found';
    } else {
      errorMessage.value = 'Failed to load role';
    }
  } finally {
    loading.value = false;
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  loading.value = true;

  try {
    await adminApi.roles.update(roleId, form);
    successMessage.value = 'Role updated successfully!';
    setTimeout(() => {
      router.push('/admin/roles');
    }, 1500);
  } catch (error) {
    loading.value = false;
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update role';
    }
  }
};

onMounted(async () => {
  try {
    const [permissionsRes] = await Promise.all([
      adminApi.permissions.index({ per_page: 1000 })
    ]);
    permissions.value = permissionsRes.data.data || [];

    await loadRole();
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>

