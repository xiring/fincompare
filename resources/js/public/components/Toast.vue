<template>
  <Teleport to="body">
    <Transition name="toast">
      <div
        v-if="visible"
        class="fixed top-4 right-4 z-50 max-w-sm rounded-lg shadow-lg p-4"
        :class="[
          type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
          type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
          type === 'warning' ? 'bg-yellow-50 text-yellow-800 border border-yellow-200' :
          'bg-blue-50 text-blue-800 border border-blue-200'
        ]"
      >
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0">
            <CheckCircleSolidIcon v-if="type === 'success'" />
            <ErrorIcon v-else-if="type === 'error'" />
            <InfoIcon v-else />
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium">{{ message }}</p>
          </div>
          <button
            @click="close"
            class="flex-shrink-0 text-gray-400 hover:text-gray-600"
          >
            <CloseXIcon />
          </button>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useToastStore } from '../stores/toast';
import { CheckCircleSolidIcon, ErrorIcon, InfoIcon, CloseXIcon } from './icons';

const toastStore = useToastStore();
const visible = ref(false);
const message = ref('');
const type = ref('info');
let timeoutId = null;

watch(() => toastStore.toast, (newToast) => {
  if (newToast) {
    visible.value = true;
    message.value = newToast.message;
    type.value = newToast.type;

    if (timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      close();
    }, newToast.duration || 3000);
  }
});

const close = () => {
  visible.value = false;
  if (timeoutId) clearTimeout(timeoutId);
  setTimeout(() => {
    toastStore.clear();
  }, 300);
};
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.toast-leave-to {
  transform: translateX(100%);
  opacity: 0;
}
</style>
