<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">{{ TEXT.SECTION_FREQUENTLY_ASKED }}</h1>
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
          <template v-slot:default="{ index: _index }">
            <div class="h-5 bg-gray-200 rounded w-3/4 mb-3"></div>
            <div class="h-4 bg-gray-100 rounded w-full"></div>
          </template>
        </LoadingSkeleton>
      </div>

      <!-- Error State -->
      <ErrorState
        v-else-if="error"
        :title="ERROR_MESSAGES.FAQS.LOAD"
        :message="error"
        @retry="loadFaqs"
      />

      <!-- FAQs List -->
      <div v-else class="bg-white border rounded-2xl divide-y">
        <div
          v-for="(faq, idx) in faqs"
          :key="faq.id || idx"
          class="p-5 animate-fade-in-up"
        >
          <button
            @click="toggleFaq(idx)"
            class="w-full flex items-center justify-between text-left"
          >
            <span class="font-medium text-gray-900">{{ faq.question }}</span>
            <ChevronDownIcon
              :class="openFaqs[idx] ? 'rotate-180' : ''"
              className="h-5 w-5 text-gray-500 transition"
            />
          </button>
          <div
            v-show="openFaqs[idx]"
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
              <span class="font-medium text-gray-900">{{ TEXT.FAQ_FREE_USE_Q }}</span>
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
              {{ TEXT.FAQ_FREE_USE_A }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { apiService } from '../services/api';
import { useSEO, useErrorHandling } from '../composables';
import { TEXT, ERROR_MESSAGES } from '../utils';
import { ChevronDownIcon } from '../components/icons';
import { ErrorState, LoadingSkeleton } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';
import type { Faq } from '../../types/index';

const faqs = ref<Faq[]>([]);
const openFaqs = ref<Record<number, boolean>>({});
const loading = ref<boolean>(true);
const { error, handleError, clearError } = useErrorHandling();

useSEO({
  title: TEXT.FAQ,
  description: TEXT.SEO_FAQ_DESCRIPTION,
  keywords: TEXT.SEO_KEYWORDS_FAQ,
});

const toggleFaq = (index: number): void => {
  openFaqs.value[index] = !openFaqs.value[index];
};

const loadFaqs = async (): Promise<void> => {
  loading.value = true;
  clearError();

  try {
    const response = await apiService.getFaqs();
    faqs.value = ((response.data as any).data || response.data || []) as Faq[];
  } catch (err: any) {
    handleError(err, ERROR_MESSAGES.FAQS.LOAD_DETAIL);
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
