<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit CMS Page</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update CMS page information</p>
    </div>

    <LoadingSpinner v-if="loading && !page" text="Loading CMS page..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="page" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Title <span class="text-red-500">*</span>
          </label>
          <input
            id="title"
            v-model="form.title"
            type="text"
            required
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.title }"
          />
          <p v-if="errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.title }}</p>
        </div>

        <div>
          <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Slug
          </label>
          <input
            id="slug"
            v-model="form.slug"
            type="text"
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.slug }"
          />
          <p v-if="errors.slug" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.slug }}</p>
        </div>

        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Status <span class="text-red-500">*</span>
          </label>
          <select
            id="status"
            v-model="form.status"
            required
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.status }"
          >
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
          <p v-if="errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.status }}</p>
        </div>

        <div>
          <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Content
          </label>
          <textarea
            id="content"
            v-model="form.content"
            rows="12"
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.content }"
          ></textarea>
          <p v-if="errors.content" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.content }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label for="seo_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              SEO Title
            </label>
            <input
              id="seo_title"
              v-model="form.seo_title"
              type="text"
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div>
            <label for="seo_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              SEO Description
            </label>
            <textarea
              id="seo_description"
              v-model="form.seo_description"
              rows="2"
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            ></textarea>
          </div>
          <div>
            <label for="seo_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              SEO Keywords
            </label>
            <input
              id="seo_keywords"
              v-model="form.seo_keywords"
              type="text"
              placeholder="keyword1, keyword2, keyword3"
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/admin/cms-pages"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
            <span>{{ loading ? 'Updating...' : 'Update CMS Page' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { adminApi } from '../../services/api';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const route = useRoute();
const router = useRouter();
const pageId = route.params.id;

const page = ref(null);
const form = reactive({
  title: '',
  slug: '',
  content: '',
  status: 'draft',
  seo_title: '',
  seo_description: '',
  seo_keywords: ''
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);

const loadPage = async () => {
  loading.value = true;
  try {
    const response = await adminApi.cmsPages.show(pageId);
    page.value = response.data;

    form.title = page.value.title || '';
    form.slug = page.value.slug || '';
    form.content = page.value.content || '';
    form.status = page.value.status || 'draft';
    form.seo_title = page.value.seo_title || '';
    form.seo_description = page.value.seo_description || '';
    form.seo_keywords = page.value.seo_keywords || '';
  } catch (error) {
    console.error('Error loading CMS page:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'CMS page not found';
    } else {
      errorMessage.value = 'Failed to load CMS page';
    }
  } finally {
    loading.value = false;
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  loading.value = true;

  try {
    await adminApi.cmsPages.update(pageId, form);
    successMessage.value = 'CMS page updated successfully!';
    setTimeout(() => {
      router.push('/admin/cms-pages');
    }, 1500);
  } catch (error) {
    loading.value = false;
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

