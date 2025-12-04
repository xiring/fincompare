<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-16"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6">{{ title }}</h2>
      <div class="bg-white border rounded-2xl divide-y">
        <div
          v-for="(faq, index) in faqs"
          :key="index"
          class="p-5"
        >
          <button
            @click="toggleFaq(index)"
            class="w-full flex items-center justify-between text-left"
          >
            <span class="font-medium text-gray-900">{{ faq.question }}</span>
            <ChevronDownIcon
              :class="openFaqs[index] ? 'rotate-180' : ''"
              class="h-5 w-5 text-gray-500 transition"
            />
          </button>
          <div
            v-show="openFaqs[index]"
            v-cloak
            class="mt-2 text-sm text-gray-600"
          >
            {{ faq.answer }}
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { ChevronDownIcon } from '../icons';

const props = defineProps({
  faqs: {
    type: Array,
    required: true,
    validator: (faqs) => {
      return faqs.every(faq => faq.question && faq.answer);
    }
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Frequently asked questions'
  }
});

const sectionRef = ref(null);
const openFaqs = ref({});

const toggleFaq = (index) => {
  openFaqs.value[index] = !openFaqs.value[index];
};
</script>

<style scoped>
[v-cloak] {
  display: none;
}
</style>

