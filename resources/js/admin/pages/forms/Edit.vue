<template>
  <div>
    <PageHeader title="Edit Form" description="Update form information" />

    <LoadingSpinner v-if="loading && !formData" text="Loading form..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="formData">
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
          id="slug"
          v-model="form.slug"
          label="Slug"
          :error="errors.slug"
        />

        <FormTextarea
          id="description"
          v-model="form.description"
          label="Description"
          :rows="3"
          :error="errors.description"
        />

        <FormSelect
          id="status"
          v-model="form.status"
          label="Status"
          :options="statusOptions"
          required
          :error="errors.status"
        />

        <FormActions
          :loading="loading"
          submit-text="Update Form"
          loading-text="Updating..."
          cancel-route="/admin/forms"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useFormsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const route = useRoute();
const router = useRouter();
const formId = route.params.id;

const formsStore = useFormsStore();
const formData = computed(() => formsStore.currentItem);

const form = reactive({
  name: '',
  slug: '',
  description: '',
  status: 'active'
});

const statusOptions = [
  { id: 'active', name: 'Active' },
  { id: 'inactive', name: 'Inactive' }
];

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = computed(() => formsStore.loading);

const loadForm = async () => {
  try {
    await formsStore.fetchItem(formId);
    if (formData.value) {
      form.name = formData.value.name || '';
      form.slug = formData.value.slug || '';
      form.description = formData.value.description || '';
      form.status = formData.value.status || 'active';
    }
  } catch (error) {
    console.error('Error loading form:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Form not found';
    } else {
      errorMessage.value = 'Failed to load form';
    }
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await formsStore.updateItem(formId, form);
    successMessage.value = 'Form updated successfully!';
    setTimeout(() => {
      router.push('/admin/forms');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update form';
    }
  }
};

onMounted(() => {
  loadForm();
});
</script>
