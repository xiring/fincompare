/**
 * Site Settings Store
 * Centralized state management for site settings
 */

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { apiService } from '../services/api';
import { DEFAULT_SITE_SETTINGS } from '../utils/constants';

export const useSiteSettingsStore = defineStore('siteSettings', () => {
  const settings = ref(null);
  const loading = ref(false);
  const error = ref(null);

  // Initialize from window if available
  if (typeof window !== 'undefined' && window.__SITE_SETTINGS__) {
    settings.value = window.__SITE_SETTINGS__;
  }

  /**
   * Fetch site settings from API
   */
  const fetchSettings = async () => {
    // Return cached if available
    if (settings.value) {
      return settings.value;
    }

    loading.value = true;
    error.value = null;

    try {
      const response = await apiService.getSiteSettings();
      settings.value = response.data;
      return settings.value;
    } catch (err) {
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
  const refresh = async () => {
    settings.value = null;
    return fetchSettings();
  };

  // Computed properties
  const siteSettings = computed(() => settings.value || DEFAULT_SITE_SETTINGS);
  const siteName = computed(() => siteSettings.value?.site_name || 'FinCompare');
  const siteDescription = computed(() => siteSettings.value?.seo_description || 'Compare financial products and find the best deals');
  const siteKeywords = computed(() => siteSettings.value?.seo_keywords || 'financial comparison, loans, credit cards');

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

