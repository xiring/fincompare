<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Attribute</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update attribute information</p>
    </div>

    <LoadingSpinner v-if="loading && !attribute" text="Loading attribute..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="attribute" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
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
          <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Type <span class="text-red-500">*</span>
          </label>
          <select
            id="type"
            v-model="form.type"
            required
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.type }"
          >
            <option value="string">String</option>
            <option value="number">Number</option>
            <option value="boolean">Boolean</option>
            <option value="json">JSON</option>
          </select>
          <p v-if="errors.type" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.type }}</p>
        </div>

        <div>
          <label for="product_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Product Category
          </label>
          <select
            id="product_category_id"
            v-model="form.product_category_id"
            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :class="{ 'border-red-300 dark:border-red-600': errors.product_category_id }"
          >
            <option :value="null">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
          <p v-if="errors.product_category_id" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.product_category_id }}</p>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/admin/attributes"
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
            <span>{{ loading ? 'Updating...' : 'Update Attribute' }}</span>
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
const attributeId = route.params.id;

const attribute = ref(null);
const form = reactive({
  name: '',
  type: 'string',
  product_category_id: null
});

const categories = ref([]);
const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);

const loadAttribute = async () => {
  loading.value = true;
  try {
    // Attributes don't have a show endpoint, so we'll need to fetch from index
    const response = await adminApi.attributes.index({ per_page: 1000 });
    const allAttributes = response.data.data || [];
    attribute.value = allAttributes.find(a => a.id == attributeId);

    if (attribute.value) {
      form.name = attribute.value.name || '';
      form.type = attribute.value.type || 'string';
      form.product_category_id = attribute.value.product_category_id || null;
    } else {
      errorMessage.value = 'Attribute not found';
    }
  } catch (error) {
    console.error('Error loading attribute:', error);
    errorMessage.value = 'Failed to load attribute';
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
    await adminApi.attributes.update(attributeId, form);
    successMessage.value = 'Attribute updated successfully!';
    setTimeout(() => {
      router.push('/admin/attributes');
    }, 1500);
  } catch (error) {
    loading.value = false;
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update attribute';
    }
  }
};

onMounted(async () => {
  try {
    const [categoriesRes] = await Promise.all([
      adminApi.productCategories.index({ per_page: 100 })
    ]);
    categories.value = categoriesRes.data.data || [];

    await loadAttribute();
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>

