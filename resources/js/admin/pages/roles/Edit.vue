<template>
  <div>
    <PageHeader title="Edit Role" description="Update role information" />

    <LoadingSpinner v-if="loading && !role" text="Loading role..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="role">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          :error="getError(errors, 'name')"
        />

        <FormCheckbox
          id="permissions"
          v-model="form.permissions"
          label="Permissions"
          :options="permissions"
          multiple
          :error="getError(errors, 'permissions')"
        />

        <FormActions
          :loading="loading"
          submit-text="Update Role"
          loading-text="Updating..."
          cancel-route="/admin/roles"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useRolesStore, usePermissionsStore } from '../../stores';
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
const roleId = route.params.id as string;

const rolesStore = useRolesStore();
const permissionsStore = usePermissionsStore();
const role = computed(() => rolesStore.currentItem);

interface FormData {
  name: string;
  permissions: Array<string | number>;
}

const form = reactive<FormData>({
  name: '',
  permissions: [],
});

const permissions = computed(() => permissionsStore.items);
const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loading = computed(() => rolesStore.loading || permissionsStore.loading);

const loadRole = async (): Promise<void> => {
  try {
    await rolesStore.fetchItem(roleId);
    if (role.value) {
      form.name = role.value.name || '';
      form.permissions = (role.value.permissions || []).map((p: any) => p.id);
    }
  } catch (error: any) {
    console.error('Error loading role:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Role not found';
    } else {
      errorMessage.value = 'Failed to load role';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await rolesStore.updateItem(roleId, form as any);
    successMessage.value = 'Role updated successfully!';
    setTimeout(() => {
      router.push('/admin/roles');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update role';
    }
  }
};

onMounted(async () => {
  try {
    await permissionsStore.fetchItems({ per_page: 1000 });
    await loadRole();
  } catch (error: any) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
