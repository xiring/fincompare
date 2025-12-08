<template>
  <GuestLayout>
    <HeroSection :title="TEXT.PRIVACY_POLICY" :subtitle="TEXT.HERO_PRIVACY_SUBTITLE" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 prose animate-fade-in-up">
      <h2>Introduction</h2>
      <p>{{ TEXT.PRIVACY_DESCRIPTION }}</p>
      <h2>Information we collect</h2>
      <ul>
        <li>Contact details you provide (name, email, phone)</li>
        <li>Usage data (pages visited, interactions)</li>
      </ul>
      <h2>How we use information</h2>
      <ul>
        <li>To respond to inquiries and provide services</li>
        <li>To improve our website and offerings</li>
      </ul>
      <h2>Your choices</h2>
      <p>You may request access or deletion of your data by contacting us.</p>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { useSEO } from '../composables';
import { TEXT } from '../utils';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

useSEO({
  title: TEXT.PRIVACY_POLICY,
  description: TEXT.SEO_PRIVACY_DESCRIPTION,
  keywords: TEXT.SEO_KEYWORDS_PRIVACY,
});
</script>

<style scoped>
.prose {
  @apply text-gray-700;
}

.prose h2 {
  @apply font-semibold text-gray-900 mt-8 mb-4;
}

.prose ul {
  @apply list-disc pl-6 mb-4;
}

.prose li {
  @apply mb-2;
}

.prose p {
  @apply mb-4;
}
</style>
