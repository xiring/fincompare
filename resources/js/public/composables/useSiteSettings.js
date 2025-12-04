/**
 * Site settings composable with caching
 */

import { ref, readonly, computed } from 'vue';
import { useAsyncData } from './useAsyncData';
import { apiService } from '../services/api';
import { DEFAULT_SITE_SETTINGS } from '../utils/constants';

// Global cache
const siteSettingsCache = ref(null);

export function useSiteSettings() {
  // Check window for initial settings
  if (typeof window !== 'undefined' && window.__SITE_SETTINGS__) {
    siteSettingsCache.value = window.__SITE_SETTINGS__;
  }

  const {
    data,
    loading,
    error,
    execute: fetchSiteSettings,
    refresh,
  } = useAsyncData(
    async () => {
      // Return cached if available
      if (siteSettingsCache.value) {
        return siteSettingsCache.value;
      }

      try {
        const response = await apiService.getSiteSettings();
        siteSettingsCache.value = response.data;
        return siteSettingsCache.value;
      } catch (err) {
        console.error('Failed to fetch site settings:', err);
        // Return defaults on error
        return DEFAULT_SITE_SETTINGS;
      }
    },
    { immediate: false }
  );

  // Initialize from window if available
  if (typeof window !== 'undefined' && window.__SITE_SETTINGS__ && !data.value) {
    data.value = window.__SITE_SETTINGS__;
    siteSettingsCache.value = window.__SITE_SETTINGS__;
  }

  return {
    siteSettings: computed(() => data.value || DEFAULT_SITE_SETTINGS),
    loading: readonly(loading),
    error: readonly(error),
    fetchSiteSettings,
    refresh,
  };
}
