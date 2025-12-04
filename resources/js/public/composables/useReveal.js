import { ref } from 'vue';

export function useReveal(threshold = 0.18) {
    const visible = ref(false);
    const elementRef = ref(null);

    const init = () => {
        if (!elementRef.value) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        visible.value = true;
                        observer.disconnect();
                    }
                });
            },
            { threshold }
        );

        observer.observe(elementRef.value);
    };

    return {
        visible,
        elementRef,
        init
    };
}
