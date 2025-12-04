import { ref, onMounted, onUnmounted } from 'vue';

/**
 * Composable for reveal animations using Intersection Observer
 * @param {number} threshold - Intersection threshold (0-1)
 * @param {HTMLElement|null} element - Optional element to observe (if not provided, uses elementRef)
 * @returns {Object} Reveal utilities
 */
export function useReveal(threshold = 0.18, element = null) {
    const visible = ref(false);
    const elementRef = ref(null);
    let observer = null;

    const init = (el = null) => {
        const targetElement = el || element || elementRef.value;
        if (!targetElement) return;

        // Disconnect existing observer if any
        if (observer) {
            observer.disconnect();
        }

        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        visible.value = true;
                        if (observer) {
                            observer.disconnect();
                            observer = null;
                        }
                    }
                });
            },
            { threshold }
        );

        observer.observe(targetElement);
    };

    onUnmounted(() => {
        if (observer) {
            observer.disconnect();
            observer = null;
        }
    });

    return {
        visible,
        elementRef,
        init
    };
}
