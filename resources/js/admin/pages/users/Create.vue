<template>
  <div>
    <PageHeader title="Create User" description="Add a new user to the system" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
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
          required
          :error="errors.password"
        />

        <FormInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          label="Confirm Password"
          type="password"
          required
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
          submit-text="Save User"
          loading-text="Creating..."
          cancel-route="/admin/users"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUsersStore, useRolesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const router = useRouter();
const usersStore = useUsersStore();
const rolesStore = useRolesStore();

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

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await usersStore.createItem(form);
    successMessage.value = 'User created successfully!';
    setTimeout(() => {
      router.push('/admin/users');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create user';
    }
  }
};

onMounted(async () => {
  try {
    await rolesStore.fetchItems();
  } catch (error) {
    console.error('Error loading roles:', error);
  }
});
</script>
