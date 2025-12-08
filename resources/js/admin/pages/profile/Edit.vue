<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your profile information and password</p>
    </div>

    <LoadingSpinner v-if="loading && !user" text="Loading profile..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="user" class="space-y-6">
      <!-- Profile Information -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profile Information</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Update your account's profile information and email address.</p>

        <form @submit.prevent="handleProfileUpdate" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Name <span class="text-red-500">*</span>
            </label>
            <input
              id="name"
              v-model="profileForm.name"
              type="text"
              required
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-300 dark:border-red-600': profileErrors.name }"
            />
            <p v-if="profileErrors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ profileErrors.name }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email <span class="text-red-500">*</span>
            </label>
            <input
              id="email"
              v-model="profileForm.email"
              type="email"
              required
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-300 dark:border-red-600': profileErrors.email }"
            />
            <p v-if="profileErrors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ profileErrors.email }}</p>
          </div>

          <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button
              type="submit"
              :disabled="profileLoading"
              class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <LoadingSpinner v-if="profileLoading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
              <span>{{ profileLoading ? 'Saving...' : 'Save' }}</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Update Password -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Password</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Ensure your account is using a long, random password to stay secure.</p>

        <form @submit.prevent="handlePasswordUpdate" class="space-y-6">
          <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Current Password <span class="text-red-500">*</span>
            </label>
            <input
              id="current_password"
              v-model="passwordForm.current_password"
              type="password"
              required
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-300 dark:border-red-600': passwordErrors.current_password }"
            />
            <p v-if="passwordErrors.current_password" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ passwordErrors.current_password }}</p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              New Password <span class="text-red-500">*</span>
            </label>
            <input
              id="password"
              v-model="passwordForm.password"
              type="password"
              required
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-300 dark:border-red-600': passwordErrors.password }"
            />
            <p v-if="passwordErrors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ passwordErrors.password }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Must be at least 8 characters</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Confirm Password <span class="text-red-500">*</span>
            </label>
            <input
              id="password_confirmation"
              v-model="passwordForm.password_confirmation"
              type="password"
              required
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-300 dark:border-red-600': passwordErrors.password_confirmation }"
            />
            <p v-if="passwordErrors.password_confirmation" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ passwordErrors.password_confirmation }}</p>
          </div>

          <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button
              type="submit"
              :disabled="passwordLoading"
              class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <LoadingSpinner v-if="passwordLoading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
              <span>{{ passwordLoading ? 'Updating...' : 'Update Password' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { adminApi } from '../../services/api';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const user = ref(null);
const loading = ref(false);
const profileLoading = ref(false);
const passwordLoading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

const profileForm = reactive({
  name: '',
  email: ''
});

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: ''
});

const profileErrors = ref({});
const passwordErrors = ref({});

const loadUser = async () => {
  loading.value = true;
  try {
    // Get current user from API
    const response = await adminApi.profile.show();
    user.value = response.data;

    profileForm.name = user.value.name || '';
    profileForm.email = user.value.email || '';
  } catch (error) {
    console.error('Error loading user:', error);
    errorMessage.value = 'Failed to load profile';
  } finally {
    loading.value = false;
  }
};

const handleProfileUpdate = async () => {
  profileErrors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  profileLoading.value = true;

  try {
    await adminApi.profile.update(profileForm);
    successMessage.value = 'Profile updated successfully!';
    setTimeout(() => {
      successMessage.value = '';
      loadUser(); // Reload user data
    }, 2000);
  } catch (error) {
    profileLoading.value = false;
    if (error.response?.status === 422) {
      profileErrors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update profile';
    }
  }
};

const handlePasswordUpdate = async () => {
  passwordErrors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  passwordLoading.value = true;

  try {
    await adminApi.profile.updatePassword(passwordForm);
    successMessage.value = 'Password updated successfully!';

    // Clear password form
    passwordForm.current_password = '';
    passwordForm.password = '';
    passwordForm.password_confirmation = '';

    setTimeout(() => {
      successMessage.value = '';
    }, 2000);
  } catch (error) {
    passwordLoading.value = false;
    if (error.response?.status === 422) {
      passwordErrors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update password';
    }
  }
};

onMounted(() => {
  loadUser();
});
</script>

