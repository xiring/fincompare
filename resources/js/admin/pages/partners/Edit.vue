<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Partner</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update partner information</p>
    </div>

    <LoadingSpinner v-if="loading && !partner" text="Loading partner..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="partner" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="space-y-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Name <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.name }}</p>
            </div>

            <div>
              <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Slug (optional)
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
              <label for="website_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Website URL
              </label>
              <input
                id="website_url"
                v-model="form.website_url"
                type="url"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.website_url }"
              />
              <p v-if="errors.website_url" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.website_url }}</p>
            </div>

            <div>
              <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Contact Email
              </label>
              <input
                id="contact_email"
                v-model="form.contact_email"
                type="email"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.contact_email }"
              />
              <p v-if="errors.contact_email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.contact_email }}</p>
            </div>

            <div>
              <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Contact Phone
              </label>
              <input
                id="contact_phone"
                v-model="form.contact_phone"
                type="text"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.contact_phone }"
              />
              <p v-if="errors.contact_phone" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.contact_phone }}</p>
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
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <p v-if="errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.status }}</p>
            </div>
          </div>

          <div class="space-y-6">
            <div>
              <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Logo
              </label>
              <div v-if="partner.logo_path && !logoPreview" class="mt-2 mb-2">
                <img :src="`/storage/${partner.logo_path}`" alt="Current logo" class="h-32 w-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700" />
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
                <img :src="logoPreview" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700" />
              </div>
              <p v-if="errors.logo" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.logo }}</p>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/admin/partners"
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
            <span>{{ loading ? 'Updating...' : 'Update Partner' }}</span>
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
const partnerId = route.params.id;

const partner = ref(null);
const form = reactive({
  name: '',
  slug: '',
  website_url: '',
  contact_email: '',
  contact_phone: '',
  status: 'active',
  logo: null
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);
const logoPreview = ref(null);

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

const loadPartner = async () => {
  loading.value = true;
  try {
    const response = await adminApi.partners.show(partnerId);
    partner.value = response.data;

    form.name = partner.value.name || '';
    form.slug = partner.value.slug || '';
    form.website_url = partner.value.website_url || '';
    form.contact_email = partner.value.contact_email || '';
    form.contact_phone = partner.value.contact_phone || '';
    form.status = partner.value.status || 'active';
  } catch (error) {
    console.error('Error loading partner:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Partner not found';
    } else {
      errorMessage.value = 'Failed to load partner';
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
    await adminApi.partners.update(partnerId, form);
    successMessage.value = 'Partner updated successfully!';
    setTimeout(() => {
      router.push('/admin/partners');
    }, 1500);
  } catch (error) {
    loading.value = false;
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update partner';
    }
  }
};

onMounted(() => {
  loadPartner();
});
</script>

