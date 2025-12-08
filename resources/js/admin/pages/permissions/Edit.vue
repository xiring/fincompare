<template>
  <div>
    <PageHeader title="Edit Permission" description="Update permission information" />

    <LoadingSpinner v-if="loading && !permission" text="Loading permission..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="permission">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          placeholder="e.g., products.create, users.edit"
          hint="Use dot notation: resource.action (e.g., products.create)"
          :error="errors.name"
        />

        <FormActions
          :loading="loading"
          submit-text="Update Permission"
          loading-text="Updating..."
          cancel-route="/admin/permissions"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { usePermissionsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const route = useRoute();
const router = useRouter();
const permissionId = route.params.id;

const permissionsStore = usePermissionsStore();
const permission = computed(() => permissionsStore.currentItem);

const form = reactive({
  name: ''
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = computed(() => permissionsStore.loading);

const loadPermission = async () => {
  try {
    await permissionsStore.fetchItem(permissionId);
    if (permission.value) {
      form.name = permission.value.name || '';
    }
  } catch (error) {
    console.error('Error loading permission:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Permission not found';
    } else {
      errorMessage.value = 'Failed to load permission';
    }
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await permissionsStore.updateItem(permissionId, form);
    successMessage.value = 'Permission updated successfully!';
    setTimeout(() => {
      router.push('/admin/permissions');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update permission';
    }
  }
};

onMounted(() => {
  loadPermission();
});
</script>
