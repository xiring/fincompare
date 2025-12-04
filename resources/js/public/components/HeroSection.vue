<template>
  <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
      <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <slot name="breadcrumb" />
      <h1 class="text-3xl font-extrabold tracking-tight">{{ title }}</h1>
      <p v-if="subtitle" class="mt-2 text-white/90">{{ subtitle }}</p>
      <slot />
    </div>
  </section>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  }
});
</script>

