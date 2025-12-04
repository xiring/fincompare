<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-12"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="rounded-2xl bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white px-6 py-8 flex flex-col md:flex-row items-center justify-between">
        <div class="mb-4 md:mb-0">
          <h3 class="text-xl font-bold">{{ title }}</h3>
          <p class="text-white/90 mt-1">{{ subtitle }}</p>
        </div>
        <router-link
          :to="actionUrl"
          class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-[color:var(--brand-primary)] font-semibold shadow hover:bg-white/90"
        >
          {{ actionText }}
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { TEXT } from '../../utils';

defineProps({
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: TEXT.CTA_TITLE
  },
  subtitle: {
    type: String,
    default: TEXT.CTA_SUBTITLE
  },
  actionUrl: {
    type: String,
    default: '/products'
  },
  actionText: {
    type: String,
    default: TEXT.GET_STARTED
  }
});

const sectionRef = ref(null);
</script>

