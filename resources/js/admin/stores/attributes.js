/**
 * Attributes Store
 * Manages attributes state and operations
 */

import { defineStore } from 'pinia';
import { ref } from 'vue';
import { adminApi } from '../services/api';

export const useAttributesStore = defineStore('attributes', () => {
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
      const response = await adminApi.attributes.index(params);
      const data = response.data;
      items.value = data.data || data || [];
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
      const response = await adminApi.attributes.edit(id);
      currentItem.value = response.data;
      return currentItem.value;
    } catch (err) {
      error.value = err;
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const fetchByCategory = async (categoryId) => {
    loading.value = true;
    error.value = null;
    try {
      const response = await adminApi.attributes.byCategory(categoryId);
      return response.data;
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
      const response = await adminApi.attributes.create(data);
      items.value.push(response.data);
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
      const response = await adminApi.attributes.update(id, data);
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
      await adminApi.attributes.delete(id);
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
    fetchByCategory,
    createItem,
    updateItem,
    deleteItem,
    clearCurrentItem,
  };
});

