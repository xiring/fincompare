/**
 * Virtual Scrolling Utility
 * Lightweight implementation for rendering large lists efficiently
 *
 * Usage: Only needed when displaying 100+ items at once (e.g., per_page > 50)
 * With current pagination (5 items per page), this is not needed.
 */

import { ref, computed, onMounted, onUnmounted } from 'vue';

/**
 * Virtual scrolling composable
 * @param {Object} options - Configuration options
 * @param {number} options.itemHeight - Height of each item in pixels
 * @param {number} options.containerHeight - Height of the scrollable container
 * @param {number} options.overscan - Number of items to render outside visible area (default: 3)
 * @returns {Object} Virtual scroll state and methods
 */
export function useVirtualScroll(options = {}) {
  const {
    itemHeight = 60,
    containerHeight = 600,
    overscan = 3,
  } = options;

  const scrollTop = ref(0);
  const containerRef = ref(null);

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
  const handleScroll = (event) => {
    scrollTop.value = event.target.scrollTop;
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
 * @param {Array} items - Full list of items
 * @param {number} startIndex - Starting index
 * @param {number} endIndex - Ending index
 * @returns {Array} Visible items with their virtual indices
 */
export function getVisibleItems(items, startIndex, endIndex) {
  return items.slice(startIndex, endIndex).map((item, index) => ({
    item,
    virtualIndex: startIndex + index,
  }));
}

