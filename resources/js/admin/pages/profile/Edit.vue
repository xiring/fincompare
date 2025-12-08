<template>
  <div>
    <PageHeader title="Profile" description="Update your profile information and password" />

    <LoadingSpinner v-if="loading && !user" text="Loading profile..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="user" class="space-y-6">
      <!-- Profile Information -->
      <FormCard>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Profile Information</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Update your account's profile information and email address.</p>

        <form @submit.prevent="handleProfileUpdate" class="space-y-6">
          <FormInput
            id="name"
            v-model="profileForm.name"
            label="Name"
            type="text"
            required
            :error="profileErrors.name"
          />

          <FormInput
            id="email"
            v-model="profileForm.email"
            label="Email"
            type="email"
            required
            :error="profileErrors.email"
          />

          <FormActions
            :loading="profileLoading"
            submit-text="Save"
            loading-text="Saving..."
            :show-submit="true"
          />
        </form>
      </FormCard>

      <!-- Update Password -->
      <FormCard>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Password</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Ensure your account is using a long, random password to stay secure.</p>

        <form @submit.prevent="handlePasswordUpdate" class="space-y-6">
          <FormInput
            id="current_password"
            v-model="passwordForm.current_password"
            label="Current Password"
            type="password"
            required
            :error="passwordErrors.current_password"
          />

          <FormInput
            id="password"
            v-model="passwordForm.password"
            label="New Password"
            type="password"
            required
            hint="Must be at least 8 characters"
            :error="passwordErrors.password"
          />

          <FormInput
            id="password_confirmation"
            v-model="passwordForm.password_confirmation"
            label="Confirm Password"
            type="password"
            required
            :error="passwordErrors.password_confirmation"
          />

          <FormActions
            :loading="passwordLoading"
            submit-text="Update Password"
            loading-text="Updating..."
            :show-submit="true"
          />
        </form>
      </FormCard>
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
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormActions from '../../components/FormActions.vue';

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
      loadUser();
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
