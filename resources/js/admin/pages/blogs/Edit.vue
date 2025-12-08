<template>
  <div>
    <PageHeader title="Edit Blog Post" description="Update blog post information" />

    <LoadingSpinner v-if="loading && !blog" text="Loading blog post..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="blog">
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
            <div v-if="blog.featured_image && !form.featured_image" class="mb-4">
              <label class="block text-sm font-medium text-charcoal-700">
                Current Image
              </label>
              <img :src="`/storage/${blog.featured_image}`" alt="Current image" class="h-32 w-full object-cover rounded-lg border border-charcoal-200" />
            </div>
            <FormFileInput
              id="featured_image"
              v-model="form.featured_image"
              label="Featured Image"
              accept="image/*"
              hint="JPG, PNG, GIF or WebP. Max size: 5MB. Leave empty to keep current image."
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
          submit-text="Update Blog Post"
          loading-text="Updating..."
          cancel-route="/admin/blogs"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
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
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const blogId = route.params.id as string;

const blogsStore = useBlogsStore();
const blog = computed(() => blogsStore.currentItem);

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

const loadBlog = async (): Promise<void> => {
  try {
    await blogsStore.fetchItem(blogId);
    if (blog.value) {
      form.title = blog.value.title || '';
      form.slug = blog.value.slug || '';
      form.category = blog.value.category || '';
      form.content = blog.value.content || '';
      form.status = (blog.value.status as 'draft' | 'published' | 'archived') || 'draft';
      form.seo_title = blog.value.seo_title || '';
      form.seo_description = blog.value.seo_description || '';
      form.seo_keywords = blog.value.seo_keywords || '';
    }
  } catch (error: any) {
    console.error('Error loading blog:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Blog post not found';
    } else {
      errorMessage.value = 'Failed to load blog post';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
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

    await blogsStore.updateItem(blogId, formData as any);
    successMessage.value = 'Blog post updated successfully!';
    setTimeout(() => {
      router.push('/admin/blogs');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update blog post';
    }
  }
};

onMounted(() => {
  loadBlog();
});
</script>
