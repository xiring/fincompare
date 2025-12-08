<template>
  <div>
    <PageHeader title="Create CMS Page" description="Add a new CMS page" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
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
          hint="Leave empty to auto-generate from title"
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

        <FormTextarea
          id="content"
          v-model="form.content"
          label="Content"
          :rows="12"
          :error="errors.content"
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
          submit-text="Save CMS Page"
          loading-text="Creating..."
          cancel-route="/admin/cms-pages"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useCmsPagesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormSection from '../../components/FormSection.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const router = useRouter();
const cmsPagesStore = useCmsPagesStore();

const form = reactive({
  title: '',
  slug: '',
  content: '',
  status: 'draft',
  seo_title: '',
  seo_description: '',
  seo_keywords: ''
});

const statusOptions = [
  { id: 'draft', name: 'Draft' },
  { id: 'published', name: 'Published' }
];

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = computed(() => cmsPagesStore.loading);

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!form.slug && form.title) {
      form.slug = form.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    await cmsPagesStore.createItem(form);
    successMessage.value = 'CMS page created successfully!';
    setTimeout(() => {
      router.push('/admin/cms-pages');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create CMS page';
    }
  }
};
</script>
