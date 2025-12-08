<template>
  <div>
    <PageHeader title="Create Form" description="Add a new form" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Column: Basic Form Information -->
          <div class="space-y-6">
            <FormSection title="Form Information">
            <FormInput
              id="name"
              v-model="form.name"
              label="Name"
              type="text"
              required
              :error="getError('name')"
            />

            <FormInput
              id="slug"
              v-model="form.slug"
              label="Slug"
              hint="Leave empty to auto-generate from name"
              :error="getError('slug')"
            />

            <FormTextarea
              id="description"
              v-model="form.description"
              label="Description"
              :rows="3"
              :error="getError('description')"
            />

            <FormSelect
              id="type"
              v-model="form.type"
              label="Type"
              :options="typeOptions"
              required
              :error="getError('type')"
            />

            <FormSelect
              id="status"
              v-model="form.status"
              label="Status"
              :options="statusOptions"
              required
              :error="getError('status')"
            />
            </FormSection>
          </div>

          <!-- Right Column: Form Inputs Section -->
          <div class="space-y-6">
          <FormSection title="Form Inputs" description="Add fields to your form">
          <div class="flex items-center justify-between mb-4">
            <button
              type="button"
              @click="addInput"
              class="inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors"
            >
              <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Add Input
            </button>
          </div>

          <div v-if="form.inputs.length === 0" class="text-center py-8 text-charcoal-500 text-sm">
            No inputs added yet. Click "Add Input" to get started.
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="(input, index) in form.inputs"
              :key="input._id || index"
              class="bg-charcoal-50 rounded-lg p-4 border border-charcoal-200"
            >
              <div class="flex items-start justify-between mb-4">
                <h4 class="font-medium text-charcoal-800">Input #{{ index + 1 }}</h4>
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    @click="moveInput(index, 'up')"
                    :disabled="index === 0"
                    class="p-1 text-charcoal-500 hover:text-charcoal-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Move up"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                  </button>
                  <button
                    type="button"
                    @click="moveInput(index, 'down')"
                    :disabled="index === form.inputs.length - 1"
                    class="p-1 text-charcoal-500 hover:text-charcoal-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Move down"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <button
                    type="button"
                    @click="removeInput(index)"
                    class="p-1 text-red-600 hover:text-red-800"
                    title="Remove"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <FormInput
                  :id="`input-label-${index}`"
                  v-model="input.label"
                  label="Label"
                  type="text"
                  required
                  :error="getError(`inputs.${index}.label`)"
                />

                <FormInput
                  :id="`input-name-${index}`"
                  v-model="input.name"
                  label="Name"
                  type="text"
                  required
                  hint="Lowercase letters, numbers, and underscores only"
                  :error="getError(`inputs.${index}.name`)"
                />

                <FormSelect
                  :id="`input-type-${index}`"
                  v-model="input.type"
                  label="Type"
                  :options="inputTypeOptions"
                  required
                  :error="getError(`inputs.${index}.type`)"
                />

                <div class="flex items-center pt-6">
                  <input
                    :id="`input-required-${index}`"
                    v-model="input.is_required"
                    type="checkbox"
                    class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
                  />
                  <label :for="`input-required-${index}`" class="ml-2 block text-sm font-medium text-charcoal-700">
                    Required
                  </label>
                </div>

                <FormInput
                  :id="`input-placeholder-${index}`"
                  v-model="input.placeholder"
                  label="Placeholder"
                  type="text"
                  :error="getError(`inputs.${index}.placeholder`)"
                />

                <FormInput
                  :id="`input-help-text-${index}`"
                  v-model="input.help_text"
                  label="Help Text"
                  type="text"
                  :error="getError(`inputs.${index}.help_text`)"
                />

                <FormInput
                  :id="`input-validation-${index}`"
                  v-model="input.validation_rules"
                  label="Validation Rules"
                  type="text"
                  hint="e.g., email|max:255"
                  :error="getError(`inputs.${index}.validation_rules`)"
                />
              </div>

              <!-- Options for dropdown type -->
              <div v-if="input.type === 'dropdown'" class="mt-4">
                <FormTextarea
                  :id="`input-options-${index}`"
                  v-model="input.options_text"
                  label="Options (one per line)"
                  :rows="4"
                  placeholder="Option 1&#10;Option 2&#10;Option 3"
                  hint="Enter each option on a new line"
                  :error="getError(`inputs.${index}.options`)"
                />
              </div>
            </div>
          </div>
          </FormSection>
          </div>
        </div>

        <FormActions
          :loading="loading"
          submit-text="Save Form"
          loading-text="Creating..."
          cancel-route="/admin/forms"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useFormsStore } from '../../stores';
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
import type { FormErrors } from '../../types/index';

const router = useRouter();
const formsStore = useFormsStore();

interface FormInputData {
  _id?: string;
  id?: number;
  label: string;
  name: string;
  type: 'text' | 'textarea' | 'dropdown' | 'checkbox';
  options: string[] | null;
  options_text: string;
  placeholder: string;
  help_text: string;
  is_required: boolean;
  validation_rules: string;
  sort_order: number;
}

interface FormData {
  name: string;
  slug: string;
  description: string;
  type: 'pre_form' | 'post_form';
  status: 'active' | 'inactive';
  inputs: FormInputData[];
}

const form = reactive<FormData>({
  name: '',
  slug: '',
  description: '',
  type: 'pre_form',
  status: 'active',
  inputs: [],
});

const typeOptions = [
  { id: 'pre_form', name: 'Pre Form' },
  { id: 'post_form', name: 'Post Form' },
];

const statusOptions = [
  { id: 'active', name: 'Active' },
  { id: 'inactive', name: 'Inactive' },
];

const inputTypeOptions = [
  { id: 'text', name: 'Text' },
  { id: 'textarea', name: 'Textarea' },
  { id: 'dropdown', name: 'Dropdown' },
  { id: 'checkbox', name: 'Checkbox' },
];

let inputIdCounter = 0;

const addInput = (): void => {
  form.inputs.push({
    _id: `input-${++inputIdCounter}`,
    label: '',
    name: '',
    type: 'text',
    options: null,
    options_text: '',
    placeholder: '',
    help_text: '',
    is_required: false,
    validation_rules: '',
    sort_order: form.inputs.length,
  });
};

const removeInput = (index: number): void => {
  form.inputs.splice(index, 1);
  // Update sort_order for remaining inputs
  form.inputs.forEach((input, idx) => {
    input.sort_order = idx;
  });
};

const moveInput = (index: number, direction: 'up' | 'down'): void => {
  if (direction === 'up' && index > 0) {
    const temp = form.inputs[index];
    const prev = form.inputs[index - 1];
    if (temp && prev) {
      form.inputs[index] = prev;
      form.inputs[index - 1] = temp;
      // Update sort_order
      form.inputs.forEach((input, idx) => {
        input.sort_order = idx;
      });
    }
  } else if (direction === 'down' && index < form.inputs.length - 1) {
    const temp = form.inputs[index];
    const next = form.inputs[index + 1];
    if (temp && next) {
      form.inputs[index] = next;
      form.inputs[index + 1] = temp;
      // Update sort_order
      form.inputs.forEach((input, idx) => {
        input.sort_order = idx;
      });
    }
  }
};

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loading = computed(() => formsStore.loading);

// Helper to get first error string from errors object
const getError = (field: string): string | undefined => {
  const error = errors.value[field];
  if (!error) return undefined;
  return Array.isArray(error) ? error[0] : error;
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    // Prepare inputs data
    const inputsData = form.inputs.map((input, index) => {
      const inputData: any = {
        label: input.label,
        name: input.name,
        type: input.type,
        placeholder: input.placeholder || null,
        help_text: input.help_text || null,
        is_required: input.is_required || false,
        validation_rules: input.validation_rules || null,
        sort_order: index,
      };

      // Handle options for dropdown
      if (input.type === 'dropdown' && input.options_text) {
        inputData.options = input.options_text
          .split('\n')
          .map((line) => line.trim())
          .filter((line) => line.length > 0);
      } else if (input.type === 'dropdown' && input.options && Array.isArray(input.options)) {
        inputData.options = input.options;
      }

      return inputData;
    });

    const formData = {
      ...form,
      inputs: inputsData,
    };

    await formsStore.createItem(formData);
    successMessage.value = 'Form created successfully!';
    setTimeout(() => {
      router.push('/admin/forms');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create form';
    }
  }
};
</script>
