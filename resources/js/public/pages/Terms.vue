<template>
  <GuestLayout>
    <HeroSection title="Terms & Conditions" subtitle="Please read these terms carefully before using FinCompare." />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 prose animate-fade-in-up">
      <h2>Use of Service</h2>
      <p>FinCompare provides comparison information for financial products. Content is for informational purposes only and not financial advice.</p>
      <h2>Limitation of Liability</h2>
      <p>We strive for accuracy but cannot guarantee completeness. Use at your own discretion.</p>
      <h2>Changes</h2>
      <p>We may update these terms from time to time. Continued use constitutes acceptance.</p>
    </div>
  </GuestLayout>
</template>

<script setup>
import { useSEO } from '../composables';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

useSEO({
  title: 'Terms of Service',
  description: 'Read FinCompare\'s terms and conditions. Understand the terms of use for our financial product comparison platform.',
  keywords: ['terms of service', 'terms and conditions', 'user agreement', 'legal terms']
});
</script>

<style scoped>
.prose {
  @apply text-gray-700;
}

.prose h2 {
  @apply font-semibold text-gray-900 mt-8 mb-4;
}

.prose p {
  @apply mb-4;
}
</style>
