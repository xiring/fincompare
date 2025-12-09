<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit User' : 'Create User'" :description="isEdit ? 'Update user information' : 'Add a new user to the system'" />

    <LoadingSpinner v-if="isEdit && loading && !user" text="Loading user..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || user">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          :error="getError(errors, 'name')"
        />

        <FormInput
          id="email"
          v-model="form.email"
          label="Email"
          type="email"
          required
          :error="getError(errors, 'email')"
        />

        <FormInput
          id="password"
          v-model="form.password"
          label="Password"
          type="password"
          :required="!isEdit"
          :hint="isEdit ? 'Leave empty to keep current password' : undefined"
          :error="getError(errors, 'password')"
        />

        <FormInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          label="Confirm Password"
          type="password"
          :required="!isEdit"
          :error="getError(errors, 'password_confirmation')"
        />

        <FormCheckbox
          id="roles"
          v-model="form.roles"
          label="Roles"
          :options="roles"
          multiple
          :error="getError(errors, 'roles')"
        />

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update User' : 'Save User'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/users"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useUsersStore, useRolesStore } from '../../stores';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const userId = route.params.id as string | undefined;

const usersStore = useUsersStore();
const rolesStore = useRolesStore();
const isEdit = computed(() => !!userId);
const loading = computed(() => usersStore.loading);
const user = computed(() => usersStore.currentItem);
const roles = computed(() => rolesStore.items);

interface FormData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  roles: Array<string | number>;
}

const form = reactive<FormData>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [],
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const loadUser = async (): Promise<void> => {
  if (!userId) return;

  try {
    await usersStore.fetchItem(userId);
    if (user.value) {
      form.name = user.value.name || '';
      form.email = user.value.email || '';
      form.password = '';
      form.password_confirmation = '';
      form.roles = (user.value.roles || []).map((role: any) => role.id || role);
    }
  } catch (error: any) {
    console.error('Error loading user:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'User not found';
    } else {
      errorMessage.value = 'Failed to load user';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const data: any = {
      name: form.name,
      email: form.email,
      roles: form.roles,
    };

    // Only include password if provided (for edit) or always (for create)
    if (form.password || !isEdit.value) {
      data.password = form.password;
      data.password_confirmation = form.password_confirmation;
    }

    if (isEdit.value && userId) {
      await usersStore.updateItem(userId, data);
      successMessage.value = 'User updated successfully!';
    } else {
      await usersStore.createItem(data);
      successMessage.value = 'User created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/users');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || (isEdit.value ? 'Failed to update user' : 'Failed to create user');
    }
  }
};

onMounted(async () => {
  try {
    await rolesStore.fetchItems({ per_page: 1000 });
    if (isEdit.value) {
      await loadUser();
    }
  } catch (error: any) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>

