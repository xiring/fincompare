/**
 * Authentication Store
 * Manages user authentication state
 */

import { defineStore } from 'pinia';
import { ref, computed, type Ref, type ComputedRef } from 'vue';
import { adminApi } from '../services/api/index';
import type { User } from '../types/index';

export const useAuthStore = defineStore('auth', () => {
  const user: Ref<User | null> = ref(null);
  const loading = ref<boolean>(false);
  const error = ref<any>(null);

  const isAuthenticated: ComputedRef<boolean> = computed(() => !!user.value);

  const fetchUser = async (): Promise<User> => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.profile.show();
      user.value = ((response.data as any)?.data || response.data) as User;
      return user.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateProfile = async (data: Partial<User>): Promise<User> => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.profile.update(data);
      user.value = ((response.data as any)?.data || response.data) as User;
      return user.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updatePassword = async (data: {
    current_password: string;
    password: string;
    password_confirmation: string;
  }): Promise<void> => {
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

  const logout = (): void => {
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

