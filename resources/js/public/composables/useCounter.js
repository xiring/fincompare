import { ref, onMounted } from 'vue';

export function useCounter(target, duration, mode) {
    const display = ref('0');
    const started = ref(false);
    const elementRef = ref(null);

    const format = (v, mode) => {
        if (mode === 'percent') return Math.round(v) + '%';
        if (mode === 'kplus') {
            if (v >= 100000) return Math.round(v / 1000) + 'k+';
            return Math.round(v).toLocaleString() + '+';
        }
        if (mode === 'plus') return Math.round(v).toLocaleString() + '+';
        return Math.round(v).toLocaleString();
    };

    const start = () => {
        if (started.value) return;
        started.value = true;

        const startTs = performance.now();
        const startVal = 0;
        const easeOut = (t) => 1 - Math.pow(1 - t, 3);

        const step = (now) => {
            const p = Math.min((now - startTs) / duration, 1);
            const val = startVal + (target - startVal) * easeOut(p);
            display.value = format(val, mode);
            if (p < 1) requestAnimationFrame(step);
        };

        requestAnimationFrame(step);
    };

    const init = () => {
        if (!elementRef.value) return;

        const obs = new IntersectionObserver(
            (entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) {
                        start();
                        obs.disconnect();
                    }
                });
            },
            { threshold: 0.4 }
        );

        obs.observe(elementRef.value);
    };

    return {
        display,
        elementRef,
        init
    };
}
