<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Permission' : 'Create Permission'" :description="isEdit ? 'Update permission information' : 'Add a new permission'" />

    <LoadingSpinner v-if="isEdit && loading && !permission" text="Loading permission..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || permission">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          placeholder="e.g., products.create, users.edit"
          hint="Use dot notation: resource.action (e.g., products.create)"
          :error="getError(errors, 'name')"
        />

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update Permission' : 'Save Permission'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/permissions"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { usePermissionsStore } from '../../stores';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const permissionId = route.params.id as string | undefined;

const permissionsStore = usePermissionsStore();
const isEdit = computed(() => !!permissionId);
const loading = computed(() => permissionsStore.loading);
const permission = computed(() => permissionsStore.currentItem);

interface FormData {
  name: string;
}

const form = reactive<FormData>({
  name: '',
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const loadPermission = async (): Promise<void> => {
  if (!permissionId) return;

  try {
    await permissionsStore.fetchItem(permissionId);
    if (permission.value) {
      form.name = permission.value.name || '';
    }
  } catch (error: any) {
    console.error('Error loading permission:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Permission not found';
    } else {
      errorMessage.value = 'Failed to load permission';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (isEdit.value && permissionId) {
      await permissionsStore.updateItem(permissionId, form);
      successMessage.value = 'Permission updated successfully!';
    } else {
      await permissionsStore.createItem(form);
      successMessage.value = 'Permission created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/permissions');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || (isEdit.value ? 'Failed to update permission' : 'Failed to create permission');
    }
  }
};

onMounted(() => {
  if (isEdit.value) {
    loadPermission();
  }
});
</script>

