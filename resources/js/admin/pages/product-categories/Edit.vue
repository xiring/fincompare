<template>
  <div>
    <PageHeader title="Edit Product Category" description="Update category information" />

    <LoadingSpinner v-if="loading && !category" text="Loading category..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="category">
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
          :rows="4"
          :error="errors.description"
        />

        <FormActions
          :loading="loading"
          submit-text="Update Category"
          loading-text="Updating..."
          cancel-route="/admin/product-categories"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductCategoriesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const categoryId = route.params.id as string;

const productCategoriesStore = useProductCategoriesStore();
const category = computed(() => productCategoriesStore.currentItem);

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

const loadCategory = async (): Promise<void> => {
  try {
    await productCategoriesStore.fetchItem(categoryId);
    if (category.value) {
      form.name = category.value.name || '';
      form.slug = category.value.slug || '';
      form.description = category.value.description || '';
    }
  } catch (error: any) {
    console.error('Error loading category:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Category not found';
    } else {
      errorMessage.value = 'Failed to load category';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await productCategoriesStore.updateItem(categoryId, form);
    successMessage.value = 'Category updated successfully!';
    setTimeout(() => {
      router.push('/admin/product-categories');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update category';
    }
  }
};

onMounted(() => {
  loadCategory();
});
</script>
