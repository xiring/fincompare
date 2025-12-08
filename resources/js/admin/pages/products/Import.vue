<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-charcoal-800">Import Products</h1>
      <p class="mt-1 text-sm text-charcoal-600">Import products from CSV file</p>
    </div>

    <!-- Error Message -->
    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />

    <!-- Success Message -->
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <!-- Form -->
    <div class="bg-white">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- File Upload -->
        <div>
          <label for="file" class="block text-sm font-medium text-charcoal-700">
            CSV File <span class="text-red-500">*</span>
          </label>
          <input
            id="file"
            type="file"
            accept=".csv,.txt"
            @change="handleFileChange"
            required
            class="block w-full text-sm text-charcoal-500"
            :class="{ 'border-red-300': errors.file }"
          />
          <p v-if="errors.file" class="mt-1 text-sm text-red-600">{{ errors.file }}</p>
          <p class="mt-2 text-xs text-charcoal-500">
            Supported formats: CSV, TXT (max 20MB)
          </p>
        </div>

        <!-- Delimiter -->
        <div>
          <label for="delimiter" class="block text-sm font-medium text-charcoal-700">
            Delimiter
          </label>
          <select
            id="delimiter"
            v-model="form.delimiter"
            class="block w-full px-4 py-2 border border-charcoal-300"
          >
            <option value=",">Comma (,)</option>
            <option value=";">Semicolon (;)</option>
            <option value="|">Pipe (|)</option>
            <option value="\t">Tab</option>
          </select>
          <p class="mt-1 text-xs text-charcoal-500">
            Select the delimiter used in your CSV file
          </p>
        </div>

        <!-- Has Header -->
        <div>
          <label class="flex items-center">
            <input
              type="checkbox"
              v-model="form.has_header"
              class="rounded border-charcoal-300 text-primary-500 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
            />
            <span class="ml-2 text-sm text-charcoal-700">File has header row</span>
          </label>
          <p class="mt-1 text-xs text-charcoal-500">
            Check if the first row contains column names
          </p>
        </div>

        <!-- CSV Format Info -->
        <div class="bg-blue-50">
          <h3 class="text-sm font-semibold text-blue-900">CSV Format Requirements</h3>
          <ul class="text-xs text-blue-800">
            <li><strong>Required columns:</strong> name, partner_id, product_category_id, status</li>
            <li><strong>Optional columns:</strong> slug, description, is_featured, attributes</li>
            <li><strong>Attributes:</strong> JSON string mapping attribute_id → value (e.g., {"10":"3% cashback","11":true})</li>
            <li><strong>Status:</strong> active or inactive</li>
            <li><strong>is_featured:</strong> true or false</li>
          </ul>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-charcoal-200">
          <router-link
            to="/admin/products"
            class="inline-flex items-center justify-center px-4 py-2.5 border border-charcoal-300"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            :disabled="loading || !form.file"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
            <span>{{ loading ? 'Importing...' : 'Import Products' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useProductsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const router = useRouter();
const productsStore = useProductsStore();

// Use store loading state
const loading = computed(() => productsStore.loading);

const form = reactive({
  file: null,
  delimiter: ',',
  has_header: true
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    // Validate file size (20MB max)
    if (file.size > 20 * 1024 * 1024) {
      errors.value.file = 'File size must be less than 20MB';
      form.file = null;
      return;
    }
    // Validate file type
    const validTypes = ['text/csv', 'text/plain', 'application/vnd.ms-excel'];
    const validExtensions = ['.csv', '.txt'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

    if (!validTypes.includes(file.type) && !validExtensions.includes(fileExtension)) {
      errors.value.file = 'Please upload a CSV or TXT file';
      form.file = null;
      return;
    }

    form.file = file;
    errors.value.file = '';
  } else {
    form.file = null;
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  if (!form.file) {
    errors.value.file = 'Please select a file';
    return;
  }

  try {
    // Import using store
    await productsStore.importProducts(form.file, form.delimiter, form.has_header);

    successMessage.value = 'Products import started successfully! The import is being processed in the background.';
    setTimeout(() => {
      router.push('/admin/products');
    }, 2000);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to import products';
    }
  }
};
</script>
