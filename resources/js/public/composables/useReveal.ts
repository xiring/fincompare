import { ref, onUnmounted } from 'vue';

/**
 * Composable for reveal animations using Intersection Observer
 */
export function useReveal(threshold: number = 0.18, element: HTMLElement | null = null) {
  const visible = ref<boolean>(false);
  const elementRef = ref<HTMLElement | null>(null);
  let observer: IntersectionObserver | null = null;

  const init = (el: HTMLElement | null = null): void => {
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

    observer.observe(targetElement as unknown as Element);
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
    init,
  };
}

