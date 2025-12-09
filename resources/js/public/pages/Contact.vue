<template>
  <GuestLayout>
    <HeroSection :title="TEXT.CONTACT_US" :subtitle="TEXT.HERO_CONTACT_SUBTITLE" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-up">
      <div class="bg-white border rounded-2xl p-6">
        <h2 class="font-semibold mb-3">{{ TEXT.CONTACT_SEND_MESSAGE }}</h2>
        <div v-if="success" data-success-message class="mb-4 rounded-lg bg-green-50 text-green-700 px-4 py-3 text-sm border border-green-200">
          <div class="flex items-center gap-2">
            <CheckCircleSolidIcon />
            <span>{{ SUCCESS_MESSAGES.CONTACT }}</span>
          </div>
        </div>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">{{ TEXT.LABEL_NAME }}</label>
            <input
              v-model="form.name"
              required
              type="text"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.name" class="text-xs text-red-600 mt-1">{{ errors.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">{{ TEXT.LABEL_EMAIL }}</label>
            <input
              v-model="form.email"
              required
              type="email"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.email" class="text-xs text-red-600 mt-1">{{ errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">{{ TEXT.LABEL_MESSAGE }}</label>
            <textarea
              v-model="form.message"
              required
              rows="4"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            ></textarea>
            <p v-if="errors.message" class="text-xs text-red-600 mt-1">{{ errors.message }}</p>
          </div>
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed btn-brand-primary"
          >
            {{ loading ? BUTTON_TEXT.LOADING.SENDING : TEXT.SUBMIT }}
          </button>
        </form>
      </div>
      <div class="space-y-4">
        <div v-if="siteSettings" class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">{{ TEXT.LABEL_EMAIL }}</h3>
          <p class="text-sm text-gray-700 mt-1">
            <a :href="`mailto:${siteSettings.email_address}`" class="text-[color:var(--brand-primary)] hover:underline">
              {{ siteSettings.email_address }}
            </a>
          </p>
        </div>
        <div v-if="siteSettings" class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">Office</h3>
          <p class="text-sm text-gray-700 mt-1">{{ siteSettings.address }}</p>
          <p v-if="siteSettings.contact_number" class="text-sm text-gray-700 mt-1">
            <a :href="`tel:${siteSettings.contact_number}`" class="text-[color:var(--brand-primary)] hover:underline">
              {{ siteSettings.contact_number }}
            </a>
          </p>
        </div>
        <div v-if="siteSettings?.map_url" class="bg-white border rounded-2xl overflow-hidden">
          <div class="aspect-video">
            <iframe
              :src="siteSettings.map_url"
              class="w-full h-full border-0"
              allowfullscreen
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { webService } from '../services/api';
import { useSiteSettings, useSEO, useFormSubmission } from '../composables';
import { TEXT, SUCCESS_MESSAGES, BUTTON_TEXT } from '../utils';
import { CheckCircleSolidIcon } from '../components/icons';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

const { siteSettings, fetchSiteSettings } = useSiteSettings();

interface ContactFormData {
  name: string;
  email: string;
  message: string;
  submitted_at: number; // Timestamp when form was rendered (set on mount)
}

const form = ref<ContactFormData>({
  name: '',
  email: '',
  message: '',
  submitted_at: 0, // Timestamp when form was rendered (set on mount)
});

const { loading, errors, success, submit: submitForm } = useFormSubmission(
  async (data: Record<string, any>) => {
    const formData = data as ContactFormData;
    const payload = {
      name: formData.name || '',
      email: formData.email || '',
      message: formData.message || '',
      submitted_at: parseInt(String(formData.submitted_at || Math.floor(Date.now() / 1000)), 10),
    };
    await webService.submitContact(payload);
    // Reset form after successful submission
    form.value = {
      name: '',
      email: '',
      message: '',
      submitted_at: Math.floor(Date.now() / 1000),
    };
  },
  {
    scrollToSuccess: true,
  }
);

useSEO({
  title: TEXT.CONTACT_US,
  description: TEXT.SEO_CONTACT_DESCRIPTION,
  keywords: TEXT.SEO_KEYWORDS_CONTACT,
});

const handleSubmit = (): void => {
  submitForm(form.value);
};

onMounted(() => {
  fetchSiteSettings();
  // Set submitted_at when form is rendered (for spam protection)
  form.value.submitted_at = Math.floor(Date.now() / 1000);
});
</script>
