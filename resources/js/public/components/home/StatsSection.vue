<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-8 bg-white"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div
          v-for="(stat, index) in localStats"
          :key="index"
          :style="{ animationDelay: `${index * 100}ms` }"
          :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
          class="p-5 rounded-2xl border bg-white text-center"
        >
          <div class="text-2xl font-extrabold text-gray-900">
            <span :data-target="stat.target" :data-mode="stat.mode">
              {{ stat.display }}
            </span>
          </div>
          <div class="text-xs text-gray-500 mt-1">{{ stat.label }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, watch, onMounted, reactive } from 'vue';
import { formatNumber } from '../../utils';

const props = defineProps({
  stats: {
    type: Array,
    required: true,
    validator: (stats) => {
      return stats.every(stat =>
        stat.target !== undefined &&
        stat.mode &&
        stat.label &&
        stat.display !== undefined
      );
    }
  },
  visible: {
    type: Boolean,
    default: false
  },
  threshold: {
    type: Number,
    default: 0.4
  }
});

const sectionRef = ref(null);
const statsAnimated = ref(false);

// Create local reactive copy of stats for animation
const localStats = reactive(
  props.stats.map(stat => ({ ...stat }))
);

const animateStats = () => {
  if (statsAnimated.value || !props.visible) return;

  statsAnimated.value = true;

  // Animate each stat counter using local reactive copy
  localStats.forEach((stat, index) => {
    const duration = 1400;
    const startTs = performance.now();
    const startVal = 0;
    const easeOut = (t) => 1 - Math.pow(1 - t, 3);

    const step = (now) => {
      const p = Math.min((now - startTs) / duration, 1);
      const val = startVal + (stat.target - startVal) * easeOut(p);
      stat.display = formatNumber(val, stat.mode);

      if (p < 1) {
        requestAnimationFrame(step);
      } else {
        // Ensure final value is set correctly
        stat.display = formatNumber(stat.target, stat.mode);
      }
    };

    setTimeout(() => requestAnimationFrame(step), index * 100);
  });
};

watch(() => props.visible, (isVisible) => {
  if (isVisible && !statsAnimated.value) {
    setTimeout(() => {
      animateStats();
    }, 50);
  }
});

onMounted(() => {
  if (sectionRef.value) {
    const rect = sectionRef.value.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
    if (isVisible && props.visible && !statsAnimated.value) {
      setTimeout(() => {
        if (props.visible && !statsAnimated.value) {
          animateStats();
        }
      }, 200);
    }
  }
});
</script>

