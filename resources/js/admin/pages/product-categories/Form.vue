<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Product Category' : 'Create Product Category'" :description="isEdit ? 'Update category information' : 'Add a new product category'" />

    <LoadingSpinner v-if="isEdit && loading && !category" text="Loading category..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || category">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          :error="getError(errors, 'name')"
        />

        <FormInput
          id="slug"
          v-model="form.slug"
          label="Slug"
          :hint="isEdit ? undefined : 'Leave empty to auto-generate from name'"
          :error="getError(errors, 'slug')"
        />

        <FormTextarea
          id="description"
          v-model="form.description"
          label="Description"
          :rows="4"
          :error="getError(errors, 'description')"
        />

        <GroupSelect
          id="group_id"
          v-model="form.group_id"
          label="Group"
          placeholder="-- Select Group --"
          :error="getError(errors, 'group_id')"
        />

        <!-- Image with existing image display (Edit mode only) -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-charcoal-700 mb-2">
            Category Image
          </label>
          <div v-if="isEdit && category && (category as any).image && !imagePreview" class="mt-2 mb-2">
            <img :src="`/storage/${(category as any).image}`" alt="Current image" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
            <p class="mt-1 text-xs text-charcoal-500">Current image</p>
          </div>
          <FormFileInput
            id="image"
            v-model="form.image"
            accept="image/*"
            :hint="isEdit ? 'JPG, PNG, GIF or WebP. Max size: 2MB. Leave empty to keep current image.' : 'JPG, PNG, GIF or WebP. Max size: 2MB'"
            :preview="true"
            :error="getError(errors, 'image')"
          />
          <div v-if="imagePreview" class="mt-2">
            <p class="text-xs text-charcoal-500">New image preview:</p>
            <img :src="imagePreview" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <FormSelect
            id="pre_form_id"
            v-model="form.pre_form_id"
            label="Pre Form"
            :options="preForms"
            placeholder="-- Select Pre Form --"
            :error="getError(errors, 'pre_form_id')"
          />

          <FormSelect
            id="post_form_id"
            v-model="form.post_form_id"
            label="Post Form"
            :options="postForms"
            placeholder="-- Select Post Form --"
            :error="getError(errors, 'post_form_id')"
          />
        </div>

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update Category' : 'Save Category'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/product-categories"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormSelect from '../../components/FormSelect.vue';
import GroupSelect from '../../components/GroupSelect.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import {
  useProductCategoryCreateMutation,
  useProductCategoryDetailQuery,
  useProductCategoryUpdateMutation,
} from '../../queries/productCategories';
import { useFormListQuery } from '../../queries/forms';
import type { Form, FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const categoryId = route.params.id as string | undefined;

const isEdit = computed(() => !!categoryId);
const {
  data: category,
  isLoading: detailLoading,
  error: detailError,
} = useProductCategoryDetailQuery(computed(() => (isEdit.value ? categoryId : undefined)));
const createMutation = useProductCategoryCreateMutation();
const updateMutation = useProductCategoryUpdateMutation();
const loading = computed(() => {
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value;
  return createMutation.isPending.value;
});

interface FormData {
  name: string;
  slug: string;
  description: string;
  image: File | null;
  group_id: string | number | null;
  pre_form_id: string | null;
  post_form_id: string | null;
}

const form = reactive<FormData>({
  name: '',
  slug: '',
  description: '',
  image: null,
  group_id: null,
  pre_form_id: null,
  post_form_id: null,
});

const { data: formsData } = useFormListQuery({ per_page: 1000 });
const preForms = computed<Form[]>(() => (formsData.value?.items || []).filter((f: any) => f.type === 'pre_form'));
const postForms = computed<Form[]>(() => (formsData.value?.items || []).filter((f: any) => f.type === 'post_form'));
const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const imagePreview = ref<string | null>(null);

// Watch for image changes to show preview
watch(
  () => form.image,
  (newFile) => {
    if (newFile) {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreview.value = e.target?.result as string;
      };
      reader.readAsDataURL(newFile);
    } else {
      imagePreview.value = null;
    }
  }
);

const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load category';
});

watchEffect(() => {
  if (category.value) {
    const cat = category.value as any;
    form.name = cat.name || '';
    form.slug = cat.slug || '';
    form.description = cat.description || '';
    form.group_id = cat.group_id ?? null;
    form.pre_form_id = cat.pre_form_id ? String(cat.pre_form_id) : null;
    form.post_form_id = cat.post_form_id ? String(cat.post_form_id) : null;
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
    if (!isEdit.value && !form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    const data: any = {
      name: form.name,
      slug: form.slug,
      description: form.description,
    };

    if (form.group_id !== null && form.group_id !== '') {
      data.group_id = typeof form.group_id === 'string' ? parseInt(form.group_id) : form.group_id;
    }

    // Add pre_form_id and post_form_id if they are set
    if (form.pre_form_id) {
      data.pre_form_id = typeof form.pre_form_id === 'string' ? parseInt(form.pre_form_id) : form.pre_form_id;
    }
    if (form.post_form_id) {
      data.post_form_id = typeof form.post_form_id === 'string' ? parseInt(form.post_form_id) : form.post_form_id;
    }

    // Only include image if a new file was selected
    if (form.image) {
      data.image = form.image;
    }

    if (isEdit.value && categoryId) {
      await updateMutation.mutateAsync({ id: categoryId, payload: data });
      successMessage.value = 'Category updated successfully!';
    } else {
      await createMutation.mutateAsync(data);
      successMessage.value = 'Category created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/product-categories');
    }, 1500);
  } catch (error: unknown) {
    const err = error as any;
    if (err.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value = err.response?.data?.message || (isEdit.value ? 'Failed to update category' : 'Failed to create category');
    }
  }
};

onMounted(() => {
  // queries auto-load; detail handled by hook
});
</script>

