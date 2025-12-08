/**
 * Site settings composable
 * Wrapper around siteSettings store for backward compatibility
 */

import { computed, type ComputedRef } from 'vue';
import { useSiteSettingsStore } from '../stores/siteSettings';
import type { SiteSettings } from '../types/index';

export function useSiteSettings() {
  const store = useSiteSettingsStore();

  return {
    siteSettings: computed(() => store.siteSettings) as ComputedRef<SiteSettings>,
    loading: computed(() => store.loading) as ComputedRef<boolean>,
    error: computed(() => store.error) as ComputedRef<string | null>,
    fetchSiteSettings: store.fetchSettings,
    refresh: store.refresh,
  };
}

