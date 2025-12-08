/**
 * Users Store
 * Manages users state and operations
 */

import { defineStore } from 'pinia';
import { ref } from 'vue';
import { adminApi } from '../services/api';

export const useUsersStore = defineStore('users', () => {
  const items = ref([]);
  const currentItem = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
  });

  const fetchItems = async (params = {}) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.users.index(params);
      const data = response.data;
      items.value = data.data || [];
      // Handle pagination from response.data (Laravel pagination format)
      if (data.current_page !== undefined) {
        pagination.value = {
          current_page: data.current_page || 1,
          last_page: data.last_page || 1,
          per_page: data.per_page || 10,
          total: data.total || 0,
          from: data.from || 0,
          to: data.to || 0,
          prev_page_url: data.prev_page_url || null,
          next_page_url: data.next_page_url || null,
        };
      } else if (data.meta) {
        // Fallback for meta-based pagination
        pagination.value = {
          current_page: data.meta.current_page || 1,
          last_page: data.meta.last_page || 1,
          per_page: data.meta.per_page || 10,
          total: data.meta.total || 0,
          from: data.meta.from || 0,
          to: data.meta.to || 0,
        };
      }
      return items.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const fetchItem = async (id) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.users.show(id);
      currentItem.value = response.data;
      return currentItem.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const createItem = async (data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.users.create(data);
      return response.data;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateItem = async (id, data) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.users.update(id, data);
      const index = items.value.findIndex(item => item.id === id);
      if (index !== -1) {
        items.value[index] = response.data;
      }
      if (currentItem.value?.id === id) {
        currentItem.value = response.data;
      }
      return response.data;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const deleteItem = async (id) => {
    loading.value = true;
    error.value = null;
    try {
      await adminApi.users.delete(id);
      items.value = items.value.filter(item => item.id !== id);
      if (currentItem.value?.id === id) {
        currentItem.value = null;
      }
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const clearCurrentItem = () => {
    currentItem.value = null;
  };

  return {
    items,
    currentItem,
    loading,
    error,
    pagination,
    fetchItems,
    fetchItem,
    createItem,
    updateItem,
    deleteItem,
    clearCurrentItem,
  };
});

