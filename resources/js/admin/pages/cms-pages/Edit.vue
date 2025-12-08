<template>
  <div>
    <PageHeader title="Edit CMS Page" description="Update CMS page information" />

    <LoadingSpinner v-if="loading && !page" text="Loading CMS page..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="page">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="title"
          v-model="form.title"
          label="Title"
          type="text"
          required
          :error="errors.title"
        />

        <FormInput
          id="slug"
          v-model="form.slug"
          label="Slug"
          :error="errors.slug"
        />

        <FormSelect
          id="status"
          v-model="form.status"
          label="Status"
          :options="statusOptions"
          required
          :error="errors.status"
        />

        <FormWysiwyg
          id="content"
          v-model="form.content"
          label="Content"
          :error="errors.content"
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
          submit-text="Update CMS Page"
          loading-text="Updating..."
          cancel-route="/admin/cms-pages"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCmsPagesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
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
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const pageId = route.params.id as string;

const cmsPagesStore = useCmsPagesStore();
const page = computed(() => cmsPagesStore.currentItem);

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

const statusOptions = [
  { id: 'draft', name: 'Draft' },
  { id: 'published', name: 'Published' },
];

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loading = computed(() => cmsPagesStore.loading);

const loadPage = async (): Promise<void> => {
  try {
    await cmsPagesStore.fetchItem(pageId);
    if (page.value) {
      form.title = page.value.title || '';
      form.slug = page.value.slug || '';
      form.content = page.value.content || '';
      form.status = (page.value.status as 'draft' | 'published') || 'draft';
      form.seo_title = page.value.seo_title || '';
      form.seo_description = page.value.seo_description || '';
      form.seo_keywords = page.value.seo_keywords || '';
    }
  } catch (error: any) {
    console.error('Error loading CMS page:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'CMS page not found';
    } else {
      errorMessage.value = 'Failed to load CMS page';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await cmsPagesStore.updateItem(pageId, form);
    successMessage.value = 'CMS page updated successfully!';
    setTimeout(() => {
      router.push('/admin/cms-pages');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update CMS page';
    }
  }
};

onMounted(() => {
  loadPage();
});
</script>
