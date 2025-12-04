<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">Get Started</h1>
        <p class="mt-2 text-white/90">Tell us about your needs and we'll help you find the right product.</p>
      </div>
    </section>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div v-if="product" class="mb-6 p-4 bg-white border rounded-lg">
        <div class="flex items-center gap-3">
          <img
            v-if="product.image_url"
            :src="productImageUrl"
            :alt="product.name"
            class="w-12 h-12 rounded-lg object-cover"
          />
          <div>
            <h3 class="font-semibold">{{ product.name }}</h3>
            <p class="text-sm text-gray-600">{{ product.partner?.name }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white border rounded-2xl p-6 md:p-8">
        <div v-if="success" class="mb-6 rounded-md bg-green-50 text-green-700 px-4 py-3 text-sm">
          <div class="flex items-center gap-2">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Thanks! Your inquiry has been received.</span>
          </div>
        </div>
        <form v-if="!success" @submit.prevent="submitForm" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input
              v-model="form.full_name"
              name="full_name"
              required
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.full_name" class="text-sm text-red-600 mt-1">{{ errors.full_name[0] }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="form.email"
                name="email"
                type="email"
                required
                class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
              >
              <p v-if="errors.email" class="text-sm text-red-600 mt-1">{{ errors.email[0] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Phone</label>
              <input
                v-model="form.phone"
                name="phone"
                type="tel"
                required
                class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
              >
              <p v-if="errors.phone" class="text-sm text-red-600 mt-1">{{ errors.phone[0] }}</p>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">City (Optional)</label>
            <input
              v-model="form.city"
              name="city"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.city" class="text-sm text-red-600 mt-1">{{ errors.city[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Message (Optional)</label>
            <textarea
              v-model="form.message"
              name="message"
              rows="4"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            ></textarea>
            <p v-if="errors.message" class="text-sm text-red-600 mt-1">{{ errors.message[0] }}</p>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] text-white font-semibold transition-colors disabled:opacity-50"
          >
            {{ loading ? 'Submitting...' : 'Submit Inquiry' }}
          </button>
        </form>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useHead } from '@vueuse/head';
import axios from 'axios';
import { webService } from '../services/api';
import GuestLayout from '../layouts/GuestLayout.vue';

const route = useRoute();
const router = useRouter();
const product = ref(null);
const form = ref({
  full_name: '',
  email: '',
  phone: '',
  city: '',
  message: '',
  product_id: ''
});
const errors = ref({});
const loading = ref(false);
const success = ref(false);

useHead({ title: 'Get Started' });

const productImageUrl = computed(() => {
  if (product.value?.image_url) {
    return product.value.image_url.startsWith('http')
      ? product.value.image_url
      : `/storage/${product.value.image_url}`;
  }
  return null;
});

const submitForm = async () => {
  loading.value = true;
  errors.value = {};
  success.value = false;

  try {
    const payload = {
      ...form.value,
      product_id: product.value?.id || product.value?.slug || form.value.product_id
    };

    await webService.submitLead(payload);
    success.value = true;
    // Reset form after successful submission
    form.value = {
      full_name: '',
      email: '',
      phone: '',
      city: '',
      message: '',
      product_id: product.value?.id || product.value?.slug || form.value.product_id
    };
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors;
    } else {
      errors.value = { message: ['Failed to submit. Please try again.'] };
    }
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  const productParam = route.query.product;
  if (productParam) {
    try {
      const response = await axios.get(`/api/public/products/${productParam}`);
      product.value = response.data.product;
      form.value.product_id = product.value.id || productParam;
    } catch (err) {
      console.error('Failed to fetch product:', err);
    }
  }
});
</script>
