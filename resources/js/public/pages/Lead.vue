<template>
  <GuestLayout>
    <HeroSection :title="TEXT.GET_STARTED_TITLE" :subtitle="TEXT.HERO_LEAD_SUBTITLE" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div v-if="product" class="mb-6 p-4 bg-white border rounded-2xl">
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
        <div v-if="success" data-success-message class="mb-6 rounded-lg bg-green-50 text-green-700 px-4 py-3 text-sm border border-green-200">
          <div class="flex items-center gap-2">
            <CheckCircleSolidIcon />
            <span>{{ SUCCESS_MESSAGES.LEAD }}</span>
          </div>
        </div>

        <!-- Product Loading Skeleton -->
        <div v-if="productLoading" class="mb-6 p-4 bg-gray-50 border rounded-2xl animate-pulse">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gray-200 rounded-lg"></div>
            <div class="flex-1">
              <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
              <div class="h-3 bg-gray-200 rounded w-1/2"></div>
            </div>
          </div>
        </div>

        <!-- Product Error State -->
        <div v-else-if="productError" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
          <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
              <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm text-red-700 font-medium mb-1">{{ ERROR_MESSAGES.PRODUCT.LOAD }}</p>
              <p class="text-xs text-red-600 mb-2">{{ productError }}</p>
              <button
                @click="loadProduct"
                type="button"
                class="inline-flex items-center gap-1 text-xs text-red-700 hover:text-red-800 font-medium"
              >
                <RefreshIcon class="w-3 h-3" />
                {{ TEXT.RETRY }}
              </button>
            </div>
          </div>
        </div>
        <form v-if="!success" @submit.prevent="submitForm" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700">{{ FORM_LABELS.FULL_NAME }}</label>
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
              <label class="block text-sm font-medium text-gray-700">{{ FORM_LABELS.EMAIL }}</label>
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
              <label class="block text-sm font-medium text-gray-700">{{ FORM_LABELS.PHONE }}</label>
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
            <label class="block text-sm font-medium text-gray-700">{{ FORM_LABELS.CITY }} (Optional)</label>
            <input
              v-model="form.city"
              name="city"
              class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]"
            >
            <p v-if="errors.city" class="text-sm text-red-600 mt-1">{{ errors.city[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">{{ FORM_LABELS.MESSAGE }} ({{ TEXT.OPTIONAL }})</label>
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
            class="w-full inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed btn-brand-primary"
          >
            {{ loading ? BUTTON_TEXT.LOADING.SUBMITTING : TEXT.SUBMIT_INQUIRY }}
          </button>
        </form>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { apiService, webService } from '../services/api';
import { useSEO } from '../composables';
import { TEXT, SUCCESS_MESSAGES, ERROR_MESSAGES, BUTTON_TEXT, FORM_LABELS } from '../utils';
import { CheckCircleSolidIcon, RefreshIcon } from '../components/icons';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

const route = useRoute();
const router = useRouter();
const product = ref(null);
const productLoading = ref(false);
const productError = ref(null);
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

useSEO({
  title: TEXT.GET_STARTED_TITLE,
  description: TEXT.SEO_LEAD_DESCRIPTION,
  keywords: TEXT.SEO_KEYWORDS_LEAD
});

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
      errors.value = { message: [ERROR_MESSAGES.SUBMIT] };
    }
  } finally {
    loading.value = false;
  }
};

const loadProduct = async () => {
  const productParam = route.query.product;
  if (!productParam) return;

  productLoading.value = true;
  productError.value = null;

  try {
    const response = await apiService.getProduct(productParam);
    product.value = response.data.product;
    form.value.product_id = product.value.id || productParam;
  } catch (err) {
    console.error('Failed to fetch product:', err);
    productError.value = err.response?.data?.message || err.message || ERROR_MESSAGES.PRODUCT.LOAD_INFO;
  } finally {
    productLoading.value = false;
  }
};

onMounted(() => {
  loadProduct();
});
</script>
