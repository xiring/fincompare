/**
 * Virtual Scrolling Utility
 * Lightweight implementation for rendering large lists efficiently
 *
 * Usage: Only needed when displaying 100+ items at once (e.g., per_page > 50)
 * With current pagination (5 items per page), this is not needed.
 */

import { ref, computed, onMounted, onUnmounted, type Ref, type ComputedRef } from 'vue';

interface VirtualScrollOptions {
  itemHeight?: number;
  containerHeight?: number;
  overscan?: number;
}

interface VirtualScrollReturn {
  containerRef: Ref<HTMLElement | null>;
  scrollTop: Ref<number>;
  startIndex: ComputedRef<number>;
  endIndex: ComputedRef<number>;
  totalHeight: ComputedRef<number>;
}

/**
 * Virtual scrolling composable
 */
export function useVirtualScroll(options: VirtualScrollOptions = {}): VirtualScrollReturn {
  const {
    itemHeight = 60,
    containerHeight = 600,
    overscan = 3,
  } = options;

  const scrollTop = ref<number>(0);
  const containerRef = ref<HTMLElement | null>(null);

  // Calculate visible range
  const startIndex = computed(() => {
    const index = Math.floor(scrollTop.value / itemHeight);
    return Math.max(0, index - overscan);
  });

  const endIndex = computed(() => {
    const visibleCount = Math.ceil(containerHeight / itemHeight);
    const index = startIndex.value + visibleCount + overscan * 2;
    return index;
  });

  // Calculate total height for scrollbar
  const totalHeight = computed(() => {
    // This should be passed as a prop or computed from items.length
    return 0; // Will be set by parent component
  });

  // Handle scroll event
  const handleScroll = (event: Event) => {
    const target = event.target as HTMLElement;
    scrollTop.value = target.scrollTop;
  };

  // Set up scroll listener
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

  return {
    containerRef,
    scrollTop,
    startIndex,
    endIndex,
    totalHeight,
  };
}

/**
 * Get visible items from a list
 */
export function getVisibleItems<T>(
  items: T[],
  startIndex: number,
  endIndex: number
): Array<{ item: T; virtualIndex: number }> {
  return items.slice(startIndex, endIndex).map((item, index) => ({
    item,
    virtualIndex: startIndex + index,
  }));
}

