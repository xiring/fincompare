<template>
  <GuestLayout>
    <HeroSection title="Privacy Policy" subtitle="How we collect, use, and protect your information." />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 prose animate-fade-in-up">
      <h2>Introduction</h2>
      <p>This Privacy Policy explains our practices regarding personal data. By using FinCompare, you agree to this policy.</p>
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

<script setup>
import { useSEO } from '../composables';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

useSEO({
  title: 'Privacy Policy',
  description: 'Learn how FinCompare collects, uses, and protects your personal information. Our commitment to your privacy and data security.',
  keywords: ['privacy policy', 'data protection', 'privacy', 'data security']
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
