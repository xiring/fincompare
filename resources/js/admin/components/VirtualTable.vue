<template>
  <div class="bg-white rounded-lg shadow-sm border border-charcoal-200">
    <div
      ref="containerRef"
      class="overflow-auto"
      :style="{ height: `${containerHeight}px` }"
      @scroll="handleScroll"
    >
      <div :style="{ height: `${totalHeight}px`, position: 'relative' }">
        <table class="min-w-full divide-y divide-charcoal-200">
          <thead class="bg-charcoal-50 sticky top-0 z-10">
            <slot name="header"></slot>
          </thead>
          <tbody class="bg-white">
            <!-- Spacer for items before visible range -->
            <tr v-if="startIndex > 0" :style="{ height: `${startIndex * itemHeight}px` }">
              <td :colspan="100"></td>
            </tr>
            <!-- Visible items -->
            <tr
              v-for="({ item, virtualIndex }) in visibleItems"
              :key="getItemKey(item, virtualIndex)"
              class="hover:bg-charcoal-50"
            >
              <slot name="row" :item="item" :index="virtualIndex"></slot>
            </tr>
            <!-- Spacer for items after visible range -->
            <tr v-if="endIndex < items.length" :style="{ height: `${(items.length - endIndex) * itemHeight}px` }">
              <td :colspan="100"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
  itemHeight: {
    type: Number,
    default: 60,
  },
  containerHeight: {
    type: Number,
    default: 600,
  },
  overscan: {
    type: Number,
    default: 3,
  },
  getItemKey: {
    type: Function,
    default: (item, index) => item.id || index,
  },
});

const containerRef = ref(null);
const scrollTop = ref(0);

const totalHeight = computed(() => props.items.length * props.itemHeight);

const startIndex = computed(() => {
  const index = Math.floor(scrollTop.value / props.itemHeight);
  return Math.max(0, index - props.overscan);
});

const endIndex = computed(() => {
  const visibleCount = Math.ceil(props.containerHeight / props.itemHeight);
  const index = startIndex.value + visibleCount + props.overscan * 2;
  return Math.min(props.items.length, index);
});

const visibleItems = computed(() => {
  return props.items.slice(startIndex.value, endIndex.value).map((item, index) => ({
    item,
    virtualIndex: startIndex.value + index,
  }));
});

const handleScroll = (event) => {
  scrollTop.value = event.target.scrollTop;
};

onMounted(() => {
  if (containerRef.value) {
    containerRef.value.addEventListener('scroll', handleScroll, { passive: true });
  }
});

onUnmounted(() => {
  if (containerRef.value) {
    containerRef.value.removeEventListener('scroll', handleScroll);
  }
});
</script>

