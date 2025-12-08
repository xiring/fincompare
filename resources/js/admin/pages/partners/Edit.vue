<template>
  <div>
    <PageHeader title="Edit Partner" description="Update partner information" />

    <LoadingSpinner v-if="loading && !partner" text="Loading partner..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="partner">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="space-y-6">
            <FormInput
              id="name"
              v-model="form.name"
              label="Name"
              type="text"
              required
              :error="errors.name"
            />

            <FormInput
              id="slug"
              v-model="form.slug"
              label="Slug"
              :error="errors.slug"
            />

            <FormInput
              id="website_url"
              v-model="form.website_url"
              label="Website URL"
              type="url"
              :error="errors.website_url"
            />

            <FormInput
              id="contact_email"
              v-model="form.contact_email"
              label="Contact Email"
              type="email"
              :error="errors.contact_email"
            />

            <FormInput
              id="contact_phone"
              v-model="form.contact_phone"
              label="Contact Phone"
              type="text"
              :error="errors.contact_phone"
            />

            <FormSelect
              id="status"
              v-model="form.status"
              label="Status"
              :options="statusOptions"
              required
              :error="errors.status"
            />
          </div>

          <div class="space-y-6">
            <div v-if="partner.logo_path && !form.logo" class="mb-4">
              <label class="block text-sm font-medium text-charcoal-700">
                Current Logo
              </label>
              <img :src="`/storage/${partner.logo_path}`" alt="Current logo" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
            </div>
            <FormFileInput
              id="logo"
              v-model="form.logo"
              label="Logo"
              accept="image/*"
              hint="JPG, PNG, GIF or WebP. Max size: 2MB. Leave empty to keep current logo."
              :preview="true"
              :error="errors.logo"
            />
          </div>
        </div>

        <FormActions
          :loading="loading"
          submit-text="Update Partner"
          loading-text="Updating..."
          cancel-route="/admin/partners"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { usePartnersStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const partnerId = route.params.id as string;

const partnersStore = usePartnersStore();
const partner = computed(() => partnersStore.currentItem);

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

const loadPartner = async (): Promise<void> => {
  try {
    await partnersStore.fetchItem(partnerId);
    if (partner.value) {
      form.name = partner.value.name || '';
      form.slug = partner.value.slug || '';
      form.website_url = partner.value.website_url || '';
      form.contact_email = partner.value.contact_email || '';
      form.contact_phone = partner.value.contact_phone || '';
      form.status = (partner.value.status as 'active' | 'inactive') || 'active';
    }
  } catch (error: any) {
    console.error('Error loading partner:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Partner not found';
    } else {
      errorMessage.value = 'Failed to load partner';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await partnersStore.updateItem(partnerId, form);
    successMessage.value = 'Partner updated successfully!';
    setTimeout(() => {
      router.push('/admin/partners');
    }, 1500);
  } catch (error: any) {
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
