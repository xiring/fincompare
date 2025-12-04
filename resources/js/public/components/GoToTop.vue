<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-4"
    >
      <button
        v-if="isVisible"
        @click="scrollToTop"
        class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-[9999] inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[color:var(--brand-primary)] text-white shadow-xl hover:bg-[color:var(--brand-primary-2)] transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-[color:var(--brand-primary)] focus:ring-offset-2"
        aria-label="Scroll to top"
        style="position: fixed !important; bottom: 1.5rem !important; right: 1.5rem !important;"
      >
        <ArrowUpIcon />
      </button>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { ArrowUpIcon } from './icons';

const isVisible = ref(false);
const scrollThreshold = 300; // Show button after scrolling 300px

const handleScroll = () => {
  isVisible.value = window.scrollY > scrollThreshold;
};

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  // Check initial scroll position
  handleScroll();
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

