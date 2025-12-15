<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Partner' : 'Create Partner'" :description="isEdit ? 'Update partner information' : 'Add a new partner'" />

    <LoadingSpinner v-if="isEdit && loading && !partner" text="Loading partner..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || partner">
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
              :hint="isEdit ? undefined : 'Leave empty to auto-generate from name'"
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
            <div v-if="isEdit && partner && (partner.logo_path || partner.logo) && !form.logo" class="mb-4">
              <label class="block text-sm font-medium text-charcoal-700">
                Current Logo
              </label>
              <img :src="`/storage/${partner.logo_path || partner.logo}`" alt="Current logo" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
            </div>
            <FormFileInput
              id="logo"
              v-model="form.logo"
              label="Logo"
              accept="image/*"
              :hint="isEdit ? 'JPG, PNG, GIF or WebP. Max size: 2MB. Leave empty to keep current logo.' : 'JPG, PNG, GIF or WebP. Max size: 2MB'"
              :preview="true"
              :error="getError(errors, 'logo')"
            />
          </div>
        </div>

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update Partner' : 'Save Partner'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/partners"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { ConstantOptions } from '../../constants/ConstantOptions';
import { usePartnerCreateMutation, usePartnerDetailQuery, usePartnerUpdateMutation } from '../../queries/partners';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const partnerId = route.params.id as string | undefined;

const isEdit = computed(() => !!partnerId);
const {
  data: partner,
  isLoading: detailLoading,
  error: detailError,
} = usePartnerDetailQuery(computed(() => (isEdit.value ? partnerId : undefined)));
const createMutation = usePartnerCreateMutation();
const updateMutation = usePartnerUpdateMutation();
const loading = computed(() => {
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value;
  return createMutation.isPending.value;
});

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

const statusOptions = ConstantOptions.partnerStatuses();

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load partner';
});

// Clear errors when user starts typing/changing fields
watch(() => form.name, () => {
  if (errors.value.name) {
    delete errors.value.name;
  }
});

watch(() => form.status, () => {
  if (errors.value.status) {
    delete errors.value.status;
  }
});

watch(() => form.slug, () => {
  if (errors.value.slug) {
    delete errors.value.slug;
  }
});

watchEffect(() => {
  if (partner.value) {
    form.name = partner.value.name || '';
    form.slug = partner.value.slug || '';
    form.website_url = (partner.value as any).website_url || partner.value.website || '';
    form.contact_email = (partner.value as any).contact_email || '';
    form.contact_phone = (partner.value as any).contact_phone || '';
    const statusValue = (partner.value as any).status || (partner.value as any).is_active ? 'active' : 'inactive';
    form.status = (statusValue === 'active' || statusValue === 'inactive' ? statusValue : 'active') as 'active' | 'inactive';
  }
  if (detailErrorMessage.value) {
    errorMessage.value = detailErrorMessage.value;
  }
});

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (!isEdit.value && !form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    // Ensure required fields have values
    if (!form.status) {
      form.status = 'active';
    }

    // Prepare data as plain object - let the API module convert to FormData
    const data: Record<string, any> = {
      name: form.name || '',
      status: form.status || 'active',
    };

    // Include optional fields only if they have values
    if (form.slug) {
      data.slug = form.slug;
    }
    if (form.website_url) {
      data.website_url = form.website_url;
    }
    if (form.contact_email) {
      data.contact_email = form.contact_email;
    }
    if (form.contact_phone) {
      data.contact_phone = form.contact_phone;
    }
    if (form.logo && form.logo instanceof File) {
      data.logo = form.logo;
    }

    if (isEdit.value && partnerId) {
      await updateMutation.mutateAsync({ id: partnerId, payload: data });
      successMessage.value = 'Partner updated successfully!';
    } else {
      await createMutation.mutateAsync(data);
      successMessage.value = 'Partner created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/partners');
    }, 1500);
  } catch (error: unknown) {
    const err = error as any;
    if (err?.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value =
        err?.response?.data?.message || (isEdit.value ? 'Failed to update partner' : 'Failed to create partner');
    }
  }
};
</script>

