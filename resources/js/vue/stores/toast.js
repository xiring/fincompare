import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useToastStore = defineStore('toast', () => {
    const toast = ref(null);

    const show = (message, type = 'info', duration = 3000) => {
        toast.value = { message, type, duration };
    };

    const success = (message, duration = 3000) => {
        show(message, 'success', duration);
    };

    const error = (message, duration = 3000) => {
        show(message, 'error', duration);
    };

    const warning = (message, duration = 3000) => {
        show(message, 'warning', duration);
    };

    const info = (message, duration = 3000) => {
        show(message, 'info', duration);
    };

    const clear = () => {
        toast.value = null;
    };

    return {
        toast,
        show,
        success,
        error,
        warning,
        info,
        clear
    };
});
