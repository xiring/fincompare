/**
 * Authentication Store
 * Manages user authentication state
 */

import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { adminApi } from '../services/api';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const loading = ref(false);
  const error = ref(null);

  const isAuthenticated = computed(() => !!user.value);

  const fetchUser = async () => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.profile.show();
      user.value = response.data;
      return user.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateProfile = async (data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.profile.update(data);
      user.value = response.data;
      return user.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updatePassword = async (data) => {
    loading.value = true;
    error.value = null;
    try {
      await adminApi.profile.updatePassword(data);
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const logout = () => {
    user.value = null;
    error.value = null;
  };

  return {
    user,
    loading,
    error,
    isAuthenticated,
    fetchUser,
    updateProfile,
    updatePassword,
    logout,
  };
});

