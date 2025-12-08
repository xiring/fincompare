<template>
  <div>
    <PageHeader title="Create Role" description="Add a new role" />

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

        <FormCheckbox
          id="permissions"
          v-model="form.permissions"
          label="Permissions"
          :options="permissions"
          multiple
          :error="errors.permissions"
        />

        <FormActions
          :loading="loading"
          submit-text="Save Role"
          loading-text="Creating..."
          cancel-route="/admin/roles"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useRolesStore, usePermissionsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const router = useRouter();
const rolesStore = useRolesStore();
const permissionsStore = usePermissionsStore();

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

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await rolesStore.createItem(form);
    successMessage.value = 'Role created successfully!';
    setTimeout(() => {
      router.push('/admin/roles');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create role';
    }
  }
};

onMounted(async () => {
  try {
    await permissionsStore.fetchItems({ per_page: 1000 });
  } catch (error: any) {
    console.error('Error loading permissions:', error);
  }
});
</script>
