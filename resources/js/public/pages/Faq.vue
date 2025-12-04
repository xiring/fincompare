<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">Frequently Asked Questions</h1>
        <p class="mt-2 text-white/90">Answers to common questions about FinCompare.</p>
      </div>
    </section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in-up">
      <!-- Loading State -->
      <div v-if="loading" class="bg-white border rounded-2xl divide-y">
        <LoadingSkeleton
          :count="5"
          container-class="divide-y"
          item-class="p-5"
        >
          <template #default="{ index }">
            <div class="h-5 bg-gray-200 rounded w-3/4 mb-3"></div>
            <div class="h-4 bg-gray-100 rounded w-full"></div>
          </template>
        </LoadingSkeleton>
      </div>

      <!-- Error State -->
      <ErrorState
        v-else-if="error"
        title="Failed to load FAQs"
        :message="error"
        @retry="loadFaqs"
      />

      <!-- FAQs List -->
      <div v-else class="bg-white border rounded-2xl divide-y">
        <div
          v-for="(faq, index) in faqs"
          :key="faq.id || index"
          class="p-5 animate-fade-in-up"
        >
          <button
            @click="toggleFaq(index)"
            class="w-full flex items-center justify-between text-left"
          >
            <span class="font-medium text-gray-900">{{ faq.question }}</span>
            <ChevronDownIcon
              :class="openFaqs[index] ? 'rotate-180' : ''"
              className="h-5 w-5 text-gray-500 transition"
            />
          </button>
          <div
            v-show="openFaqs[index]"
            v-cloak
            class="mt-2 text-sm text-gray-600"
          >
            {{ faq.answer }}
          </div>
        </div>
        <div v-if="faqs.length === 0" class="p-5">
          <div class="p-5">
            <button
              @click="toggleFaq(0)"
              class="w-full flex items-center justify-between text-left"
            >
              <span class="font-medium text-gray-900">Is FinCompare free to use?</span>
              <svg
                :class="openFaqs[0] ? 'rotate-180' : ''"
                class="h-5 w-5 text-gray-500 transition"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div
              v-show="openFaqs[0]"
              v-cloak
              class="mt-2 text-sm text-gray-600"
            >
              Yes. Comparing products on FinCompare is free.
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { apiService } from '../services/api';
import { useSEO, useErrorHandling } from '../composables';
import { ChevronDownIcon } from '../components/icons';
import { ErrorState, LoadingSkeleton, HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

const faqs = ref([]);
const openFaqs = ref({});
const loading = ref(true);
const { error, handleError, clearError } = useErrorHandling();

useSEO({
  title: 'FAQ',
  description: 'Frequently asked questions about FinCompare. Find answers about our financial product comparison platform, how it works, and how to get started.',
  keywords: ['faq', 'frequently asked questions', 'fincompare help', 'financial comparison help']
});

const toggleFaq = (index) => {
  openFaqs.value[index] = !openFaqs.value[index];
};

const loadFaqs = async () => {
  loading.value = true;
  clearError();

  try {
    const response = await apiService.getFaqs();
    faqs.value = response.data || [];
  } catch (err) {
    handleError(err, 'Failed to load FAQs. Please try again.');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadFaqs();
});
</script>

<style scoped>
[v-cloak] {
  display: none;
}
</style>
