<template>
  <div>
    <PageHeader title="Site Settings" description="Manage site settings" />

    <LoadingSpinner v-if="loading && !settings" text="Loading settings..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="settings">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-charcoal-800 mb-6">General Settings</h3>

            <FormInput
              id="site_name"
              v-model="form.site_name"
              label="Site Name"
              type="text"
              required
              :error="getError(errors, 'site_name')"
            />

            <FormInput
              id="site_slogon"
              v-model="form.site_slogon"
              label="Site Slogan"
              type="text"
            />

            <FormInput
              id="email_address"
              v-model="form.email_address"
              label="Email Address"
              type="email"
            />

            <FormInput
              id="contact_number"
              v-model="form.contact_number"
              label="Contact Number"
              type="text"
            />

            <FormTextarea
              id="address"
              v-model="form.address"
              label="Address"
              :rows="3"
            />

            <FormInput
              id="map_url"
              v-model="form.map_url"
              label="Map URL"
              type="url"
            />
          </div>

          <div>
            <h3 class="text-lg font-semibold text-charcoal-800 mb-6">Branding</h3>

            <div class="mb-6">
              <label class="block text-sm font-medium text-charcoal-700 mb-2">
                Logo
              </label>
              <div v-if="settings.logo && !form.logo" class="mb-3">
                <img :src="`/storage/${settings.logo}`" alt="Current logo" class="h-24 object-contain rounded-lg border border-charcoal-200" />
                <p class="mt-1.5 text-xs text-charcoal-500">Current logo</p>
              </div>
              <FormFileInput
                id="logo"
                v-model="form.logo"
                accept="image/*"
                hint="JPG, PNG, GIF or WebP. Max size: 2MB. Leave empty to keep current logo."
                :preview="true"
                :error="getError(errors, 'logo')"
              />
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-charcoal-700 mb-2">
                Favicon
              </label>
              <div v-if="settings.favicon && !form.favicon" class="mb-3">
                <img :src="`/storage/${settings.favicon}`" alt="Current favicon" class="h-12 w-12 object-contain rounded-lg border border-charcoal-200" />
                <p class="mt-1.5 text-xs text-charcoal-500">Current favicon</p>
              </div>
              <FormFileInput
                id="favicon"
                v-model="form.favicon"
                accept="image/*"
                hint="ICO, PNG. Max size: 1MB. Leave empty to keep current favicon."
                :preview="true"
                :error="getError(errors, 'favicon')"
              />
            </div>

            <h3 class="text-lg font-semibold text-charcoal-800 mb-6">Social Media</h3>

            <FormInput
              id="facebook_url"
              v-model="form.facebook_url"
              label="Facebook URL"
              type="url"
            />

            <FormInput
              id="instgram_url"
              v-model="form.instgram_url"
              label="Instagram URL"
              type="url"
            />

            <FormInput
              id="twitter_url"
              v-model="form.twitter_url"
              label="Twitter URL"
              type="url"
            />
          </div>
        </div>

        <FormSection title="SEO Settings">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <FormInput
              id="seo_titl"
              v-model="form.seo_titl"
              label="SEO Title"
              type="text"
            />
            <FormInput
              id="seo_keyword"
              v-model="form.seo_keyword"
              label="SEO Keywords"
              type="text"
              placeholder="keyword1, keyword2, keyword3"
            />
            <FormTextarea
              id="seo_description"
              v-model="form.seo_description"
              label="SEO Description"
              :rows="2"
            />
          </div>
        </FormSection>

        <FormActions
          :loading="loading"
          submit-text="Save Settings"
          loading-text="Saving..."
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watchEffect, computed } from 'vue';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormSection from '../../components/FormSection.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { useSettingsQuery, useSettingsUpdateMutation } from '../../queries/settings';
import type { FormErrors } from '../../types/index';

interface SettingsFormData {
  site_name: string;
  site_slogon: string;
  email_address: string;
  contact_number: string;
  address: string;
  map_url: string;
  logo: File | null;
  favicon: File | null;
  seo_titl: string;
  seo_keyword: string;
  seo_description: string;
  facebook_url: string;
  instgram_url: string;
  twitter_url: string;
}

const form = reactive<SettingsFormData>({
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
  twitter_url: '',
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const { data: settings, isLoading, error } = useSettingsQuery();
const updateMutation = useSettingsUpdateMutation();
const loading = computed(() => isLoading.value || updateMutation.isPending.value);

watchEffect(() => {
  if (settings.value) {
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
  }
  if (error?.value) {
    errorMessage.value = 'Failed to load settings';
  }
});

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  try {
    // Only include logo/favicon if they're files (new uploads)
    const data: any = { ...form };
    if (!(data.logo instanceof File)) {
      delete data.logo;
    }
    if (!(data.favicon instanceof File)) {
      delete data.favicon;
    }

    const response = await updateMutation.mutateAsync(data);
    const updated = (response?.data as any)?.data || response?.data;
    if (updated) {
      settings.value = updated;
    }
    successMessage.value = 'Settings updated successfully!';
    setTimeout(() => {
      successMessage.value = '';
      // Reload cached settings (will use updated value if set above)
      settings.value = updated ?? settings.value;
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update settings';
    }
  }
};
</script>
