<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit CMS Page' : 'Create CMS Page'" :description="isEdit ? 'Update CMS page information' : 'Add a new CMS page'" />

    <LoadingSpinner v-if="isEdit && loading && !page" text="Loading CMS page..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || page">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="title"
          v-model="form.title"
          label="Title"
          type="text"
          required
          :error="getError(errors, 'title')"
        />

        <FormInput
          id="slug"
          v-model="form.slug"
          label="Slug"
          :hint="isEdit ? undefined : 'Leave empty to auto-generate from title'"
          :error="getError(errors, 'slug')"
        />

        <FormSelect
          id="status"
          v-model="form.status"
          label="Status"
          :options="statusOptions"
          required
          :error="getError(errors, 'status')"
        />

        <FormWysiwyg
          id="content"
          v-model="form.content"
          label="Content"
          :error="getError(errors, 'content')"
          height="400px"
        />

        <FormSection title="SEO Settings">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <FormInput
              id="seo_title"
              v-model="form.seo_title"
              label="SEO Title"
              type="text"
            />

            <FormTextarea
              id="seo_description"
              v-model="form.seo_description"
              label="SEO Description"
              :rows="2"
            />

            <FormInput
              id="seo_keywords"
              v-model="form.seo_keywords"
              label="SEO Keywords"
              type="text"
              placeholder="keyword1, keyword2, keyword3"
            />
          </div>
        </FormSection>

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update CMS Page' : 'Save CMS Page'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/cms-pages"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormWysiwyg from '../../components/FormWysiwyg.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormSection from '../../components/FormSection.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { ConstantOptions } from '../../constants/ConstantOptions';
import { useCmsPageCreateMutation, useCmsPageDetailQuery, useCmsPageUpdateMutation } from '../../queries/cmsPages';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const pageId = route.params.id as string | undefined;

const isEdit = computed(() => !!pageId);
const {
  data: page,
  isLoading: detailLoading,
  error: detailError,
} = useCmsPageDetailQuery(computed(() => (isEdit.value ? pageId : undefined)));
const createMutation = useCmsPageCreateMutation();
const updateMutation = useCmsPageUpdateMutation();
const loading = computed(() => {
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value;
  return createMutation.isPending.value;
});

interface FormData {
  title: string;
  slug: string;
  content: string;
  status: 'draft' | 'published';
  seo_title: string;
  seo_description: string;
  seo_keywords: string;
}

const form = reactive<FormData>({
  title: '',
  slug: '',
  content: '',
  status: 'draft',
  seo_title: '',
  seo_description: '',
  seo_keywords: '',
});

const statusOptions = ConstantOptions.cmsStatuses().filter((o) => o.id !== '');

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load CMS page';
});

watchEffect(() => {
  if (page.value) {
    form.title = page.value.title || '';
    form.slug = page.value.slug || '';
    form.content = page.value.content || '';
    form.status = (page.value.status as 'draft' | 'published') || 'draft';
    form.seo_title = page.value.seo_title || '';
    form.seo_description = page.value.seo_description || '';
    form.seo_keywords = page.value.seo_keywords || '';
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
    if (!isEdit.value && !form.slug && form.title) {
      form.slug = form.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    if (isEdit.value && pageId) {
      await updateMutation.mutateAsync({ id: pageId, payload: form });
      successMessage.value = 'CMS page updated successfully!';
    } else {
      await createMutation.mutateAsync(form);
      successMessage.value = 'CMS page created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/cms-pages');
    }, 1500);
  } catch (error: unknown) {
    const err = error as any;
    if (err?.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value =
        err?.response?.data?.message || (isEdit.value ? 'Failed to update CMS page' : 'Failed to create CMS page');
    }
  }
};
</script>

