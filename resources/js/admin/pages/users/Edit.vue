<template>
  <div>
    <PageHeader title="Edit User" description="Update user information" />

    <LoadingSpinner v-if="loading && !user" text="Loading user..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="user">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          :error="errors.name"
        />

        <FormInput
          id="email"
          v-model="form.email"
          label="Email"
          type="email"
          required
          :error="errors.email"
        />

        <FormInput
          id="password"
          v-model="form.password"
          label="Password"
          type="password"
          hint="Leave empty to keep current password"
          :error="errors.password"
        />

        <FormInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          label="Confirm Password"
          type="password"
          :error="errors.password_confirmation"
        />

        <FormCheckbox
          id="roles"
          v-model="form.roles"
          label="Roles"
          :options="roles"
          multiple
          :error="errors.roles"
        />

        <FormActions
          :loading="loading"
          submit-text="Update User"
          loading-text="Updating..."
          cancel-route="/admin/users"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useUsersStore, useRolesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormActions from '../../components/FormActions.vue';

const route = useRoute();
const router = useRouter();
const userId = route.params.id;

const usersStore = useUsersStore();
const rolesStore = useRolesStore();
const user = computed(() => usersStore.currentItem);

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: []
});

const roles = computed(() => rolesStore.items);
const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = computed(() => usersStore.loading || rolesStore.loading);

const loadUser = async () => {
  try {
    await usersStore.fetchItem(userId);
    if (user.value) {
      form.name = user.value.name || '';
      form.email = user.value.email || '';
      form.roles = (user.value.roles || []).map(role => role.id);
    }
  } catch (error) {
    console.error('Error loading user:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'User not found';
    } else {
      errorMessage.value = 'Failed to load user';
    }
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const data = { ...form };
    if (!data.password) {
      delete data.password;
      delete data.password_confirmation;
    }

    await usersStore.updateItem(userId, data);
    successMessage.value = 'User updated successfully!';
    setTimeout(() => {
      router.push('/admin/users');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update user';
    }
  }
};

onMounted(async () => {
  try {
    await rolesStore.fetchItems();
    await loadUser();
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
