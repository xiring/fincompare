<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold tracking-tight">About Us</h1>
        <p class="mt-2 text-white/90">Our mission is to make financial decisions clearer and faster.</p>
      </div>
    </section>
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
import { useHead } from '@vueuse/head';
import GuestLayout from '../layouts/GuestLayout.vue';

useHead({ title: 'About Us' });
</script>
