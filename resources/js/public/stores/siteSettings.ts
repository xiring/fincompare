/**
 * Site Settings Store
 * Centralized state management for site settings
 */

import { defineStore } from 'pinia';
import { ref, computed, type Ref, type ComputedRef } from 'vue';
import { apiService } from '../services/api';
import { DEFAULT_SITE_SETTINGS } from '../utils/constants';
import type { SiteSettings } from '../types/index';

declare global {
  interface Window {
    __SITE_SETTINGS__?: SiteSettings;
  }
}

export const useSiteSettingsStore = defineStore('siteSettings', () => {
  const settings: Ref<SiteSettings | null> = ref<SiteSettings | null>(null);
  const loading = ref<boolean>(false);
  const error = ref<string | null>(null);

  // Initialize from window if available
  if (typeof window !== 'undefined' && window.__SITE_SETTINGS__) {
    settings.value = window.__SITE_SETTINGS__;
  }

  /**
   * Fetch site settings from API
   */
  const fetchSettings = async (): Promise<SiteSettings> => {
    // Return cached if available
    if (settings.value) {
      return settings.value;
    }

    loading.value = true;
    error.value = null;

    try {
      const response = await apiService.getSiteSettings();
      settings.value = ((response.data as any)?.data || response.data) as SiteSettings;
      return settings.value;
    } catch (err: any) {
      console.error('Failed to fetch site settings:', err);
      error.value = err.message || 'Failed to fetch site settings';
      // Return defaults on error
      settings.value = DEFAULT_SITE_SETTINGS;
      return settings.value;
    } finally {
      loading.value = false;
    }
  };

  /**
   * Refresh site settings
   */
  const refresh = async (): Promise<SiteSettings> => {
    settings.value = null;
    return fetchSettings();
  };

  // Computed properties
  const siteSettings: ComputedRef<SiteSettings> = computed(() => settings.value || DEFAULT_SITE_SETTINGS);
  const siteName: ComputedRef<string> = computed(() => siteSettings.value?.site_name || 'FinCompare');
  const siteDescription: ComputedRef<string> = computed(
    () => siteSettings.value?.site_description || 'Compare financial products and find the best deals'
  );
  const siteKeywords: ComputedRef<string> = computed(
    () => siteSettings.value?.seo_keywords || 'financial comparison, loans, credit cards'
  );

  return {
    // State
    settings,
    loading,
    error,
    // Computed
    siteSettings,
    siteName,
    siteDescription,
    siteKeywords,
    // Actions
    fetchSettings,
    refresh,
  };
});

