<template>
  <div class="min-h-screen bg-[var(--brand-bg)] flex flex-col font-sans antialiased theme-public text-[var(--brand-text)]">
    <Navigation />
    <Toast />

    <main class="flex-1">
      <slot />
    </main>

    <Footer />
    <PrivacyBanner />
    <GoToTop />

    <!-- Loading Overlay -->
    <Transition name="fade">
      <div v-if="loading" class="fixed inset-0 z-50 bg-white/90 backdrop-blur flex items-center justify-center" role="status" aria-live="polite">
        <div class="flex flex-col items-center">
          <LoadingSpinnerIcon className="h-8 w-8 text-[color:var(--brand-primary)]" />
          <div class="mt-3 text-sm text-gray-700">Loading…</div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import { useSiteSettingsStore } from '../stores/siteSettings';
import { getLogoUrl } from '../utils/helpers';
import { LoadingSpinnerIcon } from '../components/icons';
import Navigation from '../components/Navigation.vue';
import Footer from '../components/Footer.vue';
import Toast from '../components/Toast.vue';
import PrivacyBanner from '../components/PrivacyBanner.vue';
import GoToTop from '../components/GoToTop.vue';

const route = useRoute();
const siteSettingsStore = useSiteSettingsStore();
const loading = ref(true);

// Computed values for SEO
const siteSettings = computed(() => siteSettingsStore.siteSettings);
const siteName = computed(() => siteSettingsStore.siteName);
const siteDescription = computed(() => siteSettingsStore.siteDescription);
const siteKeywords = computed(() => siteSettingsStore.siteKeywords);
const siteLogo = computed(() => getLogoUrl(siteSettings.value?.logo));
const siteUrl = computed(() => window.location.origin);

// Update head tags reactively
watch([siteSettings, route], () => {
  if (siteSettings.value) {
    useHead({
      titleTemplate: (title) => {
        return title ? `${title} - ${siteName.value}` : siteName.value;
      },
      meta: [
        // Basic SEO
        {
          name: 'description',
          content: siteDescription.value
        },
        {
          name: 'keywords',
          content: siteKeywords.value
        },
        {
          name: 'author',
          content: siteName.value
        },
        // Open Graph
        {
          property: 'og:type',
          content: 'website'
        },
        {
          property: 'og:site_name',
          content: siteName.value
        },
        {
          property: 'og:title',
          content: route.meta.title ? `${route.meta.title} - ${siteName.value}` : siteName.value
        },
        {
          property: 'og:description',
          content: siteDescription.value
        },
        {
          property: 'og:url',
          content: siteUrl.value + route.path
        },
        ...(siteLogo.value ? [{
          property: 'og:image',
          content: siteLogo.value.startsWith('http') ? siteLogo.value : siteUrl.value + siteLogo.value
        }] : []),
        // Twitter Card
        {
          name: 'twitter:card',
          content: 'summary_large_image'
        },
        {
          name: 'twitter:title',
          content: route.meta.title ? `${route.meta.title} - ${siteName.value}` : siteName.value
        },
        {
          name: 'twitter:description',
          content: siteDescription.value
        },
        ...(siteLogo.value ? [{
          name: 'twitter:image',
          content: siteLogo.value.startsWith('http') ? siteLogo.value : siteUrl.value + siteLogo.value
        }] : []),
        // Additional meta
        {
          name: 'robots',
          content: 'index, follow'
        },
        {
          name: 'viewport',
          content: 'width=device-width, initial-scale=1'
        }
      ],
      link: [
        {
          rel: 'icon',
          href: siteSettings.value?.favicon ? `/storage/${siteSettings.value.favicon}` : '/favicon.ico'
        },
        {
          rel: 'canonical',
          href: siteUrl.value + route.path
        }
      ]
    });
  }
}, { immediate: true });

onMounted(async () => {
  await siteSettingsStore.fetchSettings();

  // Simulate page load completion
  setTimeout(() => {
    loading.value = false;
  }, 500);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
