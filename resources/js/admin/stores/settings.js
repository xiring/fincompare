/**
 * Settings Store
 * Manages site settings state and operations
 */

import { defineStore } from 'pinia';
import { ref } from 'vue';
import { adminApi } from '../services/api';

export const useSettingsStore = defineStore('settings', () => {
  const settings = ref(null);
  const loading = ref(false);
  const error = ref(null);

  const fetchSettings = async () => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.settings.show();
      settings.value = response.data?.data || response.data;
      return settings.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateSettings = async (data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.settings.update(data);
      settings.value = response.data?.data || response.data;
      return settings.value;
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
