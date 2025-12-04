<template>
  <div class="min-h-screen bg-[var(--brand-bg)] flex flex-col font-sans antialiased theme-public text-[var(--brand-text)]">
    <Navigation />
    <Toast />

    <main class="flex-1">
      <slot />
    </main>

    <Footer />
    <PrivacyBanner />

    <!-- Loading Overlay -->
    <Transition name="fade">
      <div v-if="loading" class="fixed inset-0 z-50 bg-white/90 backdrop-blur flex items-center justify-center">
        <div class="flex flex-col items-center">
          <svg class="h-8 w-8 animate-spin text-[color:var(--brand-primary)]" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
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
import { useSiteSettings } from '../composables/useSiteSettings';
import Navigation from '../components/Navigation.vue';
import Footer from '../components/Footer.vue';
import Toast from '../components/Toast.vue';
import PrivacyBanner from '../components/PrivacyBanner.vue';

const route = useRoute();
const { siteSettings, fetchSiteSettings } = useSiteSettings();
const loading = ref(true);

// Computed values for SEO
const siteName = computed(() => siteSettings.value?.site_name || 'FinCompare');
const siteDescription = computed(() => siteSettings.value?.seo_description || 'Compare financial products and find the best deals');
const siteKeywords = computed(() => siteSettings.value?.seo_keywords || 'financial comparison, loans, credit cards');
const siteLogo = computed(() => {
  if (siteSettings.value?.logo) {
    const logo = siteSettings.value.logo;
    if (logo.startsWith('http://') || logo.startsWith('https://')) {
      return logo;
    }
    if (logo.startsWith('/storage')) {
      return logo;
    }
    return `/storage/${logo}`;
  }
  return null;
});
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
  await fetchSiteSettings();

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
