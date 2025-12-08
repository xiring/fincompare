<template>
  <div>
    <PageHeader title="Create Permission" description="Add a new permission" />

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
          placeholder="e.g., products.create, users.edit"
          hint="Use dot notation: resource.action (e.g., products.create)"
          :error="errors.name"
        />

        <FormActions
          :loading="loading"
          submit-text="Save Permission"
          loading-text="Creating..."
          cancel-route="/admin/permissions"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { usePermissionsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const router = useRouter();
const permissionsStore = usePermissionsStore();

const form = reactive({
  name: ''
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = computed(() => permissionsStore.loading);

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await permissionsStore.createItem(form);
    successMessage.value = 'Permission created successfully!';
    setTimeout(() => {
      router.push('/admin/permissions');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create permission';
    }
  }
};
</script>
