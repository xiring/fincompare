import { defineStore } from 'pinia';
import { ref, type Ref } from 'vue';
import type { Toast } from '../types/index';

export const useToastStore = defineStore('toast', () => {
  const toast: Ref<Toast | null> = ref<Toast | null>(null);

  const show = (message: string, type: 'success' | 'error' | 'warning' | 'info' = 'info', duration: number = 3000): void => {
    toast.value = { id: Date.now().toString(), message, type, duration };
  };

  const success = (message: string, duration: number = 3000): void => {
    show(message, 'success', duration);
  };

  const error = (message: string, duration: number = 3000): void => {
    show(message, 'error', duration);
  };

  const warning = (message: string, duration: number = 3000): void => {
    show(message, 'warning', duration);
  };

  const info = (message: string, duration: number = 3000): void => {
    show(message, 'info', duration);
  };

  const clear = (): void => {
    toast.value = null;
  };

  return {
    toast,
    show,
    success,
    error,
    warning,
    info,
    clear,
  };
});

