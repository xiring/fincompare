<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Site Settings</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage site settings</p>
    </div>

    <LoadingSpinner v-if="loading && !settings" text="Loading settings..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="settings" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">General Settings</h3>

            <div>
              <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Site Name <span class="text-red-500">*</span>
              </label>
              <input
                id="site_name"
                v-model="form.site_name"
                type="text"
                required
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.site_name }"
              />
              <p v-if="errors.site_name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.site_name }}</p>
            </div>

            <div>
              <label for="site_slogon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Site Slogan
              </label>
              <input
                id="site_slogon"
                v-model="form.site_slogon"
                type="text"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div>
              <label for="email_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Email Address
              </label>
              <input
                id="email_address"
                v-model="form.email_address"
                type="email"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div>
              <label for="contact_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Contact Number
              </label>
              <input
                id="contact_number"
                v-model="form.contact_number"
                type="text"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div>
              <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Address
              </label>
              <textarea
                id="address"
                v-model="form.address"
                rows="3"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              ></textarea>
            </div>

            <div>
              <label for="map_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Map URL
              </label>
              <input
                id="map_url"
                v-model="form.map_url"
                type="url"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
          </div>

          <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Branding</h3>

            <div>
              <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Logo
              </label>
              <div v-if="settings.logo && !logoPreview" class="mt-2 mb-2">
                <img :src="`/storage/${settings.logo}`" alt="Current logo" class="h-24 object-contain rounded-lg border border-gray-200 dark:border-gray-700" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current logo</p>
              </div>
              <input
                id="logo"
                type="file"
                accept="image/*"
                @change="handleLogoChange"
                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400"
              />
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, GIF or WebP. Max size: 2MB. Leave empty to keep current logo.</p>
              <div v-if="logoPreview" class="mt-2">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">New logo preview:</p>
                <img :src="logoPreview" alt="Preview" class="h-24 object-contain rounded-lg border border-gray-200 dark:border-gray-700" />
              </div>
            </div>

            <div>
              <label for="favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Favicon
              </label>
              <div v-if="settings.favicon && !faviconPreview" class="mt-2 mb-2">
                <img :src="`/storage/${settings.favicon}`" alt="Current favicon" class="h-12 w-12 object-contain rounded-lg border border-gray-200 dark:border-gray-700" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current favicon</p>
              </div>
              <input
                id="favicon"
                type="file"
                accept="image/*"
                @change="handleFaviconChange"
                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400"
              />
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ICO, PNG. Max size: 1MB. Leave empty to keep current favicon.</p>
              <div v-if="faviconPreview" class="mt-2">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">New favicon preview:</p>
                <img :src="faviconPreview" alt="Preview" class="h-12 w-12 object-contain rounded-lg border border-gray-200 dark:border-gray-700" />
              </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mt-6">Social Media</h3>

            <div>
              <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Facebook URL
              </label>
              <input
                id="facebook_url"
                v-model="form.facebook_url"
                type="url"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div>
              <label for="instgram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Instagram URL
              </label>
              <input
                id="instgram_url"
                v-model="form.instgram_url"
                type="url"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div>
              <label for="twitter_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Twitter URL
              </label>
              <input
                id="twitter_url"
                v-model="form.twitter_url"
                type="url"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
          </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">SEO Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label for="seo_titl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                SEO Title
              </label>
              <input
                id="seo_titl"
                v-model="form.seo_titl"
                type="text"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              />
            </div>
            <div>
              <label for="seo_keyword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                SEO Keywords
              </label>
              <input
                id="seo_keyword"
                v-model="form.seo_keyword"
                type="text"
                placeholder="keyword1, keyword2, keyword3"
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
          </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
            <span>{{ loading ? 'Saving...' : 'Save Settings' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { adminApi } from '../../services/api';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const settings = ref(null);
const form = reactive({
  site_name: '',
  site_slogon: '',
  email_address: '',
  contact_number: '',
  address: '',
  map_url: '',
  logo: null,
  favicon: null,
  seo_titl: '',
  seo_keyword: '',
  seo_description: '',
  facebook_url: '',
  instgram_url: '',
  twitter_url: ''
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);
const logoPreview = ref(null);
const faviconPreview = ref(null);

const handleLogoChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.logo = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      logoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.logo = null;
    logoPreview.value = null;
  }
};

const handleFaviconChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.favicon = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      faviconPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.favicon = null;
    faviconPreview.value = null;
  }
};

const loadSettings = async () => {
  loading.value = true;
  try {
    const response = await adminApi.settings.show();
    settings.value = response.data;

    form.site_name = settings.value.site_name || '';
    form.site_slogon = settings.value.site_slogon || '';
    form.email_address = settings.value.email_address || '';
    form.contact_number = settings.value.contact_number || '';
    form.address = settings.value.address || '';
    form.map_url = settings.value.map_url || '';
    form.seo_titl = settings.value.seo_titl || settings.value.seo_title || '';
    form.seo_keyword = settings.value.seo_keyword || settings.value.seo_keywords || '';
    form.seo_description = settings.value.seo_description || '';
    form.facebook_url = settings.value.facebook_url || '';
    form.instgram_url = settings.value.instgram_url || settings.value.instagram_url || '';
    form.twitter_url = settings.value.twitter_url || '';
  } catch (error) {
    console.error('Error loading settings:', error);
    errorMessage.value = 'Failed to load settings';
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
    // Only include logo/favicon if they're files (new uploads)
    const data = { ...form };
    if (!(data.logo instanceof File)) {
      delete data.logo;
    }
    if (!(data.favicon instanceof File)) {
      delete data.favicon;
    }

    await adminApi.settings.update(data);
    successMessage.value = 'Settings updated successfully!';
    setTimeout(() => {
      successMessage.value = '';
      loadSettings(); // Reload to get updated file paths
    }, 1500);
  } catch (error) {
    loading.value = false;
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update settings';
    }
  }
};

onMounted(() => {
  loadSettings();
});
</script>
