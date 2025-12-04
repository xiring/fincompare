<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-16"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-8">{{ title }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(benefit, index) in benefits"
          :key="index"
          :style="{ animationDelay: `${index * 120}ms` }"
          :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
          class="p-6 bg-white border rounded-2xl"
        >
          <div class="h-10 w-10 rounded-lg flex items-center justify-center mb-3 bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] ring-1 ring-[color:var(--brand-primary)]/20">
            <component :is="benefit.icon" class="h-5 w-5" />
          </div>
          <h3 class="font-semibold">{{ benefit.title }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ benefit.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { TEXT } from '../../utils';

defineProps({
  benefits: {
    type: Array,
    required: true,
    validator: (benefits) => {
      return benefits.every(benefit =>
        benefit.title &&
        benefit.description &&
        benefit.icon
      );
    }
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: TEXT.SECTION_WHY_CHOOSE
  }
});

const sectionRef = ref(null);
</script>

