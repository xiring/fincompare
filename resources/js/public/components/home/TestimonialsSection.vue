<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-16"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">{{ title }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(testimonial, index) in testimonials"
          :key="index"
          :style="{ animationDelay: `${index * 120}ms` }"
          :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
          class="p-6 bg-white border rounded-2xl"
        >
          <p class="text-sm text-gray-700">"{{ testimonial.text }}"</p>
          <div class="mt-4 text-sm text-gray-500">— {{ testimonial.author }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { TEXT } from '../../utils';

defineProps({
  testimonials: {
    type: Array,
    required: true,
    validator: (testimonials) => {
      return testimonials.every(t => t.text && t.author);
    }
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: TEXT.SECTION_TESTIMONIALS
  }
});

const sectionRef = ref(null);
</script>

