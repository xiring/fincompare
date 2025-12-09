<template>
  <GuestLayout>
    <HeroSection :title="TEXT.ABOUT_US" :subtitle="TEXT.HERO_ABOUT_SUBTITLE" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 animate-fade-in-up">
      <p class="text-gray-700">
        {{ TEXT.ABOUT_DESCRIPTION }}
      </p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">{{ TEXT.ABOUT_RELIABLE_TITLE }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ TEXT.ABOUT_RELIABLE_DESC }}</p>
        </div>
        <div class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">{{ TEXT.ABOUT_NEUTRAL_TITLE }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ TEXT.ABOUT_NEUTRAL_DESC }}</p>
        </div>
        <div class="p-6 bg-white border rounded-2xl">
          <h3 class="font-semibold">{{ TEXT.ABOUT_PRIVACY_TITLE }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ TEXT.ABOUT_PRIVACY_DESC }}</p>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { useSEO } from '../composables';
import { TEXT } from '../utils';
import { HeroSection } from '../components';
import GuestLayout from '../layouts/GuestLayout.vue';

useSEO({
  title: TEXT.ABOUT_US,
  description: TEXT.SEO_ABOUT_DESCRIPTION,
  keywords: TEXT.SEO_KEYWORDS_ABOUT,
});
</script>
