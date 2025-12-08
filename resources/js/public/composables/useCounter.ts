import { ref } from 'vue';

type CounterMode = 'percent' | 'kplus' | 'plus' | 'default';

export function useCounter(target: number, duration: number, mode: CounterMode = 'default') {
  const display = ref<string>('0');
  const started = ref<boolean>(false);
  const elementRef = ref<HTMLElement | null>(null);

  const format = (v: number, mode: CounterMode): string => {
    if (mode === 'percent') return Math.round(v) + '%';
    if (mode === 'kplus') {
      if (v >= 100000) return Math.round(v / 1000) + 'k+';
      return Math.round(v).toLocaleString() + '+';
    }
    if (mode === 'plus') return Math.round(v).toLocaleString() + '+';
    return Math.round(v).toLocaleString();
  };

  const start = (): void => {
    if (started.value) return;
    started.value = true;

    const startTs = performance.now();
    const startVal = 0;
    const easeOut = (t: number): number => 1 - Math.pow(1 - t, 3);

    const step = (now: number): void => {
      const p = Math.min((now - startTs) / duration, 1);
      const val = startVal + (target - startVal) * easeOut(p);
      display.value = format(val, mode);
      if (p < 1) requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
  };

  const init = (): void => {
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

    obs.observe(elementRef.value as unknown as Element);
  };

  return {
    display,
    elementRef,
    init,
  };
}

