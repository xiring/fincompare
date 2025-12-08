<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update user information</p>
    </div>

    <LoadingSpinner v-if="loading && !user" text="Loading user..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="user" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
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
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.email }"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.email }}</p>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Password (leave empty to keep current)
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.password }"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.password }}</p>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Only fill if you want to change the password</p>
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.password_confirmation }"
          />
          <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.password_confirmation }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Roles
          </label>
          <div class="space-y-2">
            <label
              v-for="role in roles"
              :key="role.id"
              class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
            >
              <input
                :id="`role-${role.id}`"
                v-model="form.roles"
                type="checkbox"
                :value="role.id"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
              />
              <span class="text-sm text-gray-700 dark:text-gray-300">{{ role.name }}</span>
            </label>
            <p v-if="roles.length === 0" class="text-sm text-gray-500 dark:text-gray-400">No roles available</p>
          </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/admin/users"
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
            <span>{{ loading ? 'Updating...' : 'Update User' }}</span>
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
const userId = route.params.id;

const user = ref(null);
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: []
});

const roles = ref([]);
const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);

const loadUser = async () => {
  loading.value = true;
  try {
    const response = await adminApi.users.show(userId);
    user.value = response.data;

    form.name = user.value.name || '';
    form.email = user.value.email || '';
    form.roles = (user.value.roles || []).map(role => role.id);
  } catch (error) {
    console.error('Error loading user:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'User not found';
    } else {
      errorMessage.value = 'Failed to load user';
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
    // Remove password fields if empty
    const data = { ...form };
    if (!data.password) {
      delete data.password;
      delete data.password_confirmation;
    }

    await adminApi.users.update(userId, data);
    successMessage.value = 'User updated successfully!';
    setTimeout(() => {
      router.push('/admin/users');
    }, 1500);
  } catch (error) {
    loading.value = false;
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update user';
    }
  }
};

onMounted(async () => {
  try {
    const [rolesRes] = await Promise.all([
      adminApi.roles.index()
    ]);
    roles.value = rolesRes.data.data || rolesRes.data || [];

    await loadUser();
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>

