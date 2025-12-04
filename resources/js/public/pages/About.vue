<template>
  <GuestLayout>
    <HeroSection title="About Us" subtitle="Our mission is to make financial decisions clearer and faster." />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 animate-fade-in-up">
      <p class="text-gray-700">
        FinCompare helps people discover, compare, and choose financial products with confidence.
        We bring transparency through structured data and clean design.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">Reliable Data</h3>
          <p class="text-sm text-gray-600 mt-1">Continuously updated specs from trusted partners.</p>
        </div>
        <div class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">Neutral Guidance</h3>
          <p class="text-sm text-gray-600 mt-1">We highlight what matters; you decide.</p>
        </div>
        <div class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">Privacy First</h3>
          <p class="text-sm text-gray-600 mt-1">Your data is protected and never sold.</p>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { useSEO } from '../composables';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

useSEO({
  title: 'About Us',
  description: 'Learn about FinCompare and our mission to make financial decisions clearer and faster through transparent product comparison.',
  keywords: ['about fincompare', 'financial comparison', 'transparent finance']
});
</script>
