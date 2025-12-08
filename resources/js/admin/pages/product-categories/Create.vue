<template>
  <div>
    <PageHeader title="Create Product Category" description="Add a new product category" />

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
          :rows="4"
          :error="errors.description"
        />

        <FormActions
          :loading="loading"
          submit-text="Save Category"
          loading-text="Creating..."
          cancel-route="/admin/product-categories"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useProductCategoriesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const router = useRouter();
const productCategoriesStore = useProductCategoriesStore();

interface FormData {
  name: string;
  slug: string;
  description: string;
}

const form = reactive<FormData>({
  name: '',
  slug: '',
  description: '',
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loading = computed(() => productCategoriesStore.loading);

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    await productCategoriesStore.createItem(form);
    successMessage.value = 'Category created successfully!';
    setTimeout(() => {
      router.push('/admin/product-categories');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create category';
    }
  }
};
</script>
