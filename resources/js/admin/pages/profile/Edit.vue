<template>
  <div>
    <PageHeader title="Profile" description="Update your profile information and password" />

    <LoadingSpinner v-if="loading && !user" text="Loading profile..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="user" class="space-y-6">
      <!-- Profile Information -->
      <FormCard>
        <h2 class="text-lg font-semibold text-charcoal-800">Profile Information</h2>
        <p class="text-sm text-charcoal-600">Update your account's profile information and email address.</p>

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
        <h2 class="text-lg font-semibold text-charcoal-800">Update Password</h2>
        <p class="text-sm text-charcoal-600">Ensure your account is using a long, random password to stay secure.</p>

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

<script setup lang="ts">
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
import type { User, FormErrors } from '../../types/index';

const user = ref<User | null>(null);
const loading = ref<boolean>(false);
const profileLoading = ref<boolean>(false);
const passwordLoading = ref<boolean>(false);
const errorMessage = ref<string>('');
const successMessage = ref<string>('');

interface ProfileFormData {
  name: string;
  email: string;
}

const profileForm = reactive<ProfileFormData>({
  name: '',
  email: '',
});

interface PasswordFormData {
  current_password: string;
  password: string;
  password_confirmation: string;
}

const passwordForm = reactive<PasswordFormData>({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const profileErrors = ref<FormErrors>({});
const passwordErrors = ref<FormErrors>({});

const loadUser = async (): Promise<void> => {
  loading.value = true;
  try {
    const response = await adminApi.profile.show();
    user.value = response.data;

    profileForm.name = user.value.name || '';
    profileForm.email = user.value.email || '';
  } catch (error: any) {
    console.error('Error loading user:', error);
    errorMessage.value = 'Failed to load profile';
  } finally {
    loading.value = false;
  }
};

const handleProfileUpdate = async (): Promise<void> => {
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
  } catch (error: any) {
    profileLoading.value = false;
    if (error.response?.status === 422) {
      profileErrors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update profile';
    }
  }
};

const handlePasswordUpdate = async (): Promise<void> => {
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
  } catch (error: any) {
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
