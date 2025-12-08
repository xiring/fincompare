<template>
  <div>
    <PageHeader title="Create Blog Post" description="Add a new blog post" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="space-y-6">
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
              hint="Leave empty to auto-generate from title"
              :error="getError(errors, 'slug')"
            />

            <FormInput
              id="category"
              v-model="form.category"
              label="Category"
              type="text"
              :error="getError(errors, 'category')"
            />

            <FormSelect
              id="status"
              v-model="form.status"
              label="Status"
              :options="statusOptions"
              required
              :error="getError(errors, 'status')"
            />
          </div>

          <div class="space-y-6">
            <FormFileInput
              id="featured_image"
              v-model="form.featured_image"
              label="Featured Image"
              accept="image/*"
              hint="JPG, PNG, GIF or WebP. Max size: 5MB"
              :preview="true"
              :error="getError(errors, 'featured_image')"
            />
          </div>
        </div>

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
          submit-text="Save Blog Post"
          loading-text="Creating..."
          cancel-route="/admin/blogs"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useBlogsStore } from '../../stores';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormWysiwyg from '../../components/FormWysiwyg.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormSection from '../../components/FormSection.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const router = useRouter();
const blogsStore = useBlogsStore();

interface FormData {
  title: string;
  slug: string;
  category: string;
  content: string;
  status: 'draft' | 'published' | 'archived';
  featured_image: File | null;
  seo_title: string;
  seo_description: string;
  seo_keywords: string;
}

const form = reactive<FormData>({
  title: '',
  slug: '',
  category: '',
  content: '',
  status: 'draft',
  featured_image: null,
  seo_title: '',
  seo_description: '',
  seo_keywords: '',
});

const statusOptions = [
  { id: 'draft', name: 'Draft' },
  { id: 'published', name: 'Published' },
  { id: 'archived', name: 'Archived' },
];

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loading = computed(() => blogsStore.loading);

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!form.slug && form.title) {
      form.slug = form.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    // Create FormData for file upload
    const formData = new FormData();
    Object.keys(form).forEach((key) => {
      const value = (form as any)[key];
      if (value !== null && value !== undefined && value !== '') {
        if (key === 'featured_image' && value instanceof File) {
          formData.append(key, value);
        } else {
          formData.append(key, String(value));
        }
      }
    });

    await blogsStore.createItem(formData as any);
    successMessage.value = 'Blog post created successfully!';
    setTimeout(() => {
      router.push('/admin/blogs');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create blog post';
    }
  }
};
</script>
