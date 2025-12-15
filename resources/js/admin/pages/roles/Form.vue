<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Role' : 'Create Role'" :description="isEdit ? 'Update role information' : 'Add a new role'" />

    <LoadingSpinner v-if="isEdit && loading && !role" text="Loading role..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || role">
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
          :submit-text="isEdit ? 'Update Role' : 'Save Role'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/roles"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { useRoleCreateMutation, useRoleDetailQuery, useRoleUpdateMutation } from '../../queries/roles';
import { usePermissionListQuery } from '../../queries/permissions';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const roleId = route.params.id as string | undefined;

const isEdit = computed(() => !!roleId);
const {
  data: role,
  isLoading: detailLoading,
  error: detailError,
} = useRoleDetailQuery(computed(() => (isEdit.value ? roleId : undefined)));
const createMutation = useRoleCreateMutation();
const updateMutation = useRoleUpdateMutation();
const { data: permissionsData, isLoading: permsLoading } = usePermissionListQuery({ per_page: 1000 });
const permissions = computed(() => (permissionsData.value?.items || []) as any[]);
const loading = computed(() => {
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value || permsLoading.value;
  return createMutation.isPending.value || permsLoading.value;
});

interface FormData {
  name: string;
  permissions: Array<string | number>;
}

const form = reactive<FormData>({
  name: '',
  permissions: [],
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load role';
});

watchEffect(() => {
  if (role.value) {
    form.name = role.value.name || '';
    form.permissions = (role.value.permissions || []).map((p: any) => p.id);
  }
  if (detailErrorMessage.value) {
    errorMessage.value = detailErrorMessage.value;
  }
});

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (isEdit.value && roleId) {
      await updateMutation.mutateAsync({ id: roleId, payload: form as any });
      successMessage.value = 'Role updated successfully!';
    } else {
      await createMutation.mutateAsync(form as any);
      successMessage.value = 'Role created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/roles');
    }, 1500);
  } catch (error: unknown) {
    const err = error as any;
    if (err?.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value = err?.response?.data?.message || (isEdit.value ? 'Failed to update role' : 'Failed to create role');
    }
  }
};

onMounted(() => {
  // permissions list query auto-loads; role detail query handled by hook
});
</script>

