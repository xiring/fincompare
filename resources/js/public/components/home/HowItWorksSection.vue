<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-16 bg-gray-50"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">{{ title }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(step, index) in steps"
          :key="index"
          :style="{ animationDelay: `${index * 120}ms` }"
          :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
          class="p-6 bg-white border rounded-lg text-center"
        >
          <div class="mx-auto h-12 w-12 rounded-full flex items-center justify-center mb-3 bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] ring-1 ring-[color:var(--brand-primary)]/20">
            {{ step.number }}
          </div>
          <h3 class="font-semibold">{{ step.title }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ step.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  steps: {
    type: Array,
    required: true,
    validator: (steps) => {
      return steps.every(step =>
        step.number !== undefined &&
        step.title &&
        step.description
      );
    }
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'How FinCompare Works'
  }
});

const sectionRef = ref(null);
</script>

