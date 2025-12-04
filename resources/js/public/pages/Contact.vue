<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">Contact Us</h1>
        <p class="mt-2 text-white/90">We'd love to hear from you.</p>
      </div>
    </section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-up">
      <div class="bg-white border rounded-2xl p-6">
        <h2 class="font-semibold mb-3">Send a message</h2>
        <div v-if="success" data-success-message class="mb-4 rounded-lg bg-green-50 text-green-700 px-4 py-3 text-sm border border-green-200">
          <div class="flex items-center gap-2">
            <CheckCircleSolidIcon />
            <span>Thank you! Your message has been sent.</span>
          </div>
        </div>
        <form @submit.prevent="submitForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="form.name"
              required
              type="text"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.name" class="text-xs text-red-600 mt-1">{{ errors.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input
              v-model="form.email"
              required
              type="email"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.email" class="text-xs text-red-600 mt-1">{{ errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Message</label>
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
            {{ loading ? 'Sending...' : 'Submit' }}
          </button>
        </form>
      </div>
      <div class="space-y-4">
        <div v-if="siteSettings" class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">Email</h3>
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
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { webService } from '../services/api';
import { useSiteSettings, useSEO } from '../composables';
import { CheckCircleSolidIcon } from '../components/icons';
import GuestLayout from '../layouts/GuestLayout.vue';

const { siteSettings, fetchSiteSettings } = useSiteSettings();
const form = ref({
  name: '',
  email: '',
  message: '',
  submitted_at: 0 // Timestamp when form was rendered (set on mount)
});
const errors = ref({});
const loading = ref(false);
const success = ref(false);

useSEO({
  title: 'Contact Us',
  description: 'Get in touch with FinCompare. We\'d love to hear from you and help with any questions about our financial product comparison platform.',
  keywords: ['contact fincompare', 'customer support', 'financial advice']
});

const submitForm = async () => {
  loading.value = true;
  errors.value = {};
  success.value = false;

  try {
    const payload = {
      name: form.value.name || '',
      email: form.value.email || '',
      message: form.value.message || '',
      submitted_at: parseInt(form.value.submitted_at || Math.floor(Date.now() / 1000), 10), // Unix timestamp in seconds
    };

    await webService.submitContact(payload);
    success.value = true;
    form.value = {
      name: '',
      email: '',
      message: '',
      submitted_at: Math.floor(Date.now() / 1000) // Reset timestamp for next submission
    };
    // Scroll to success message
    await nextTick();
    const successElement = document.querySelector('[data-success-message]');
    if (successElement) {
      successElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors;
    } else {
      errors.value = { message: ['Failed to send message. Please try again.'] };
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchSiteSettings();
  // Set submitted_at when form is rendered (for spam protection)
  form.value.submitted_at = Math.floor(Date.now() / 1000);
});
</script>
