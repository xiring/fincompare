<template>
  <div>
    <PageHeader title="Create Form" description="Add a new form" />

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
          id="slug"
          v-model="form.slug"
          label="Slug"
          hint="Leave empty to auto-generate from name"
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
          submit-text="Save Form"
          loading-text="Creating..."
          cancel-route="/admin/forms"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useFormsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const router = useRouter();
const formsStore = useFormsStore();

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

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    await formsStore.createItem(form);
    successMessage.value = 'Form created successfully!';
    setTimeout(() => {
      router.push('/admin/forms');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create form';
    }
  }
};
</script>
