<template>
  <div>
    <PageHeader title="Import Products" description="Import products from CSV file" />

    <!-- Error Message -->
    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />

    <!-- Success Message -->
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <!-- Form -->
    <FormCard>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- File Upload -->
        <FormFileInput
          id="file"
          v-model="form.file"
          label="CSV File"
          accept=".csv,.txt"
          required
          :error="getError(errors, 'file')"
          hint="Supported formats: CSV, TXT (max 20MB)"
          @update:modelValue="handleFileChange"
        />

        <!-- Delimiter -->
        <FormSelect
          id="delimiter"
          v-model="form.delimiter"
          label="Delimiter"
          hint="Select the delimiter used in your CSV file"
          :options="delimiterOptions"
          option-value="value"
          option-label="label"
          :placeholder="false"
        />

        <!-- Has Header -->
        <div class="mb-6">
          <label for="has_header" class="block text-sm font-medium text-charcoal-700 mb-2">
            File has header row
          </label>
          <div class="flex items-center">
            <input
              id="has_header"
              type="checkbox"
              v-model="form.has_header"
              class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
            />
            <span class="ml-2 text-sm text-charcoal-700">Check if the first row contains column names</span>
          </div>
        </div>

        <!-- CSV Format Info -->
        <FormSection title="CSV Format Requirements">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
            <div>
              <p class="text-sm font-medium text-blue-900 mb-2">Download Sample CSV:</p>
              <a
                href="/examples/product_import_sample.csv"
                download="product_import_sample.csv"
                class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"
              >
                <DownloadIcon class="h-4 w-4 mr-1.5" />
                Download Sample CSV File
              </a>
            </div>
            <div class="border-t border-blue-200 pt-3">
              <ul class="text-sm text-blue-800 space-y-1">
                <li><strong>Required columns:</strong> name, partner_id, product_category_id, status</li>
                <li><strong>Optional columns:</strong> slug, description, is_featured, attributes</li>
                <li><strong>Attributes:</strong> JSON string mapping attribute_id → value (e.g., {"10":"3% cashback","11":true})</li>
                <li><strong>Status:</strong> active or inactive</li>
                <li><strong>is_featured:</strong> true or false</li>
              </ul>
            </div>
          </div>
        </FormSection>

        <!-- Actions -->
        <FormActions
          :loading="loading"
          :disabled="!form.file"
          submit-text="Import Products"
          loading-text="Importing..."
          cancel-route="/admin/products"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormSection from '../../components/FormSection.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { DownloadIcon } from '../../components/icons';
import type { FormErrors } from '../../types/index';
import { useProductImportMutation } from '../../queries/products';

const router = useRouter();
const importMutation = useProductImportMutation();
const loading = computed(() => importMutation.isPending.value);

interface ImportFormData {
  file: File | null;
  delimiter: string;
  has_header: boolean;
}

const form = reactive<ImportFormData>({
  file: null,
  delimiter: ',',
  has_header: true,
});

const delimiterOptions = [
  { value: ',', label: 'Comma (,)' },
  { value: ';', label: 'Semicolon (;)' },
  { value: '|', label: 'Pipe (|)' },
  { value: '\t', label: 'Tab' },
];

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const handleFileChange = (file: File | null): void => {
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
    const fileExtension = '.' + file.name.split('.').pop()?.toLowerCase();

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

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  if (!form.file) {
    errors.value.file = 'Please select a file';
    return;
  }

  try {
    await importMutation.mutateAsync({
      file: form.file,
      delimiter: form.delimiter,
      has_header: form.has_header,
    });

    successMessage.value = 'Products import started successfully! The import is being processed in the background.';
    setTimeout(() => {
      router.push('/admin/products');
    }, 2000);
  } catch (error: unknown) {
    const err = error as any;
    if (err?.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value = err?.response?.data?.message || 'Failed to import products';
    }
  }
};
</script>
