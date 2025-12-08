/**
 * Settings Store
 * Manages site settings state and operations
 */

import { defineStore } from 'pinia';
import { ref, type Ref } from 'vue';
import { adminApi } from '../services/api/index';
import type { SiteSetting } from '../types/index';

export const useSettingsStore = defineStore('settings', () => {
  const settings: Ref<SiteSetting[] | Record<string, SiteSetting> | null> = ref(null);
  const loading = ref<boolean>(false);
  const error = ref<any>(null);

  const fetchSettings = async (): Promise<SiteSetting[] | Record<string, SiteSetting>> => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.settings.show();
      settings.value = (response.data as any)?.data || response.data;
      return settings.value as SiteSetting[] | Record<string, SiteSetting>;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateSettings = async (data: Record<string, any>): Promise<SiteSetting[] | Record<string, SiteSetting>> => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.settings.update(data);
      settings.value = (response.data as any)?.data || response.data;
      return settings.value as SiteSetting[] | Record<string, SiteSetting>;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  return {
    settings,
    loading,
    error,
    fetchSettings,
    updateSettings,
  };
});

