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
      <div class="bg-white border rounded-2xl divide-y">
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
            <svg
              :class="openFaqs[index] ? 'rotate-180' : ''"
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
import { useSEO } from '../composables';
import GuestLayout from '../layouts/GuestLayout.vue';

const faqs = ref([]);
const openFaqs = ref({});

useSEO({
  title: 'FAQ',
  description: 'Frequently asked questions about FinCompare. Find answers about our financial product comparison platform, how it works, and how to get started.',
  keywords: ['faq', 'frequently asked questions', 'fincompare help', 'financial comparison help']
});

const toggleFaq = (index) => {
  openFaqs.value[index] = !openFaqs.value[index];
};

onMounted(async () => {
  try {
    const response = await apiService.getFaqs();
    faqs.value = response.data || [];
  } catch (err) {
    console.error('Failed to fetch FAQs:', err);
    // Could set error state here for better UX
  }
});
</script>

<style scoped>
[v-cloak] {
  display: none;
}
</style>
