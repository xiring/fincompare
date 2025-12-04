/**
 * Site settings composable
 * Wrapper around siteSettings store for backward compatibility
 */

import { computed } from 'vue';
import { useSiteSettingsStore } from '../stores/siteSettings';

export function useSiteSettings() {
  const store = useSiteSettingsStore();

  return {
    siteSettings: computed(() => store.siteSettings),
    loading: computed(() => store.loading),
    error: computed(() => store.error),
    fetchSiteSettings: store.fetchSettings,
    refresh: store.refresh,
  };
}
