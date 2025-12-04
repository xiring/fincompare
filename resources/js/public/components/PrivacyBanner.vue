<template>
  <Teleport to="body">
    <Transition name="cookie">
      <div 
        v-if="!accepted"
        class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t shadow-lg"
      >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex-1 text-sm text-gray-700">
              <p>
                We use cookies to improve your experience on our site. By continuing to use this website, you agree to our use of cookies.
                <router-link to="/privacy" class="text-[color:var(--brand-primary)] hover:underline ml-1">Learn more</router-link>
              </p>
            </div>
            <div class="flex items-center gap-3">
              <button 
                @click="acceptCookies"
                class="px-4 py-2 rounded-lg bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] text-white font-medium text-sm transition-colors"
              >
                Accept
              </button>
              <button 
                @click="declineCookies"
                class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium text-sm transition-colors"
              >
                Decline
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const accepted = ref(true);

onMounted(() => {
  // Check if user has already accepted cookies
  const cookieConsent = localStorage.getItem('cookie-consent');
  if (!cookieConsent) {
    accepted.value = false;
  }
});

const acceptCookies = () => {
  localStorage.setItem('cookie-consent', 'accepted');
  accepted.value = true;
};

const declineCookies = () => {
  localStorage.setItem('cookie-consent', 'declined');
  accepted.value = true;
};
</script>

<style scoped>
.cookie-enter-active,
.cookie-leave-active {
  transition: all 0.3s ease;
}

.cookie-enter-from,
.cookie-leave-to {
  transform: translateY(100%);
  opacity: 0;
}
</style>
