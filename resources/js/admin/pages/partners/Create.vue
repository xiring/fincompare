<template>
  <div>
    <PageHeader title="Create Partner" description="Add a new partner" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="space-y-6">
            <FormInput
              id="name"
              v-model="form.name"
              label="Name"
              type="text"
              required
              :error="getError(errors, 'name')"
            />

            <FormInput
              id="slug"
              v-model="form.slug"
              label="Slug"
              hint="Leave empty to auto-generate from name"
              :error="getError(errors, 'slug')"
            />

            <FormInput
              id="website_url"
              v-model="form.website_url"
              label="Website URL"
              type="url"
              :error="getError(errors, 'website_url')"
            />

            <FormInput
              id="contact_email"
              v-model="form.contact_email"
              label="Contact Email"
              type="email"
              :error="getError(errors, 'contact_email')"
            />

            <FormInput
              id="contact_phone"
              v-model="form.contact_phone"
              label="Contact Phone"
              type="text"
              :error="getError(errors, 'contact_phone')"
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
              id="logo"
              v-model="form.logo"
              label="Logo"
              accept="image/*"
              hint="JPG, PNG, GIF or WebP. Max size: 2MB"
              :preview="true"
              :error="getError(errors, 'logo')"
            />
          </div>
        </div>

        <FormActions
          :loading="loading"
          submit-text="Save Partner"
          loading-text="Creating..."
          cancel-route="/admin/partners"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { usePartnersStore } from '../../stores';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const router = useRouter();
const partnersStore = usePartnersStore();

interface FormData {
  name: string;
  slug: string;
  website_url: string;
  contact_email: string;
  contact_phone: string;
  status: 'active' | 'inactive';
  logo: File | null;
}

const form = reactive<FormData>({
  name: '',
  slug: '',
  website_url: '',
  contact_email: '',
  contact_phone: '',
  status: 'active',
  logo: null,
});

const statusOptions = [
  { id: 'active', name: 'Active' },
  { id: 'inactive', name: 'Inactive' },
];

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loading = computed(() => partnersStore.loading);

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    // Create FormData for file upload
    const formData = new FormData();
    Object.keys(form).forEach((key) => {
      const value = (form as any)[key];
      if (value !== null && value !== undefined && value !== '') {
        if (key === 'logo' && value instanceof File) {
          formData.append(key, value);
        } else {
          formData.append(key, String(value));
        }
      }
    });

    await partnersStore.createItem(formData as any);
    successMessage.value = 'Partner created successfully!';
    setTimeout(() => {
      router.push('/admin/partners');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create partner';
    }
  }
};
</script>
