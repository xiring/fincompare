/**
 * Product Categories API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/product-categories', { params }),
  show: (id) => apiClient.get(`/product-categories/${id}`),
  create: (data) => apiClient.post('/product-categories', data),
  update: (id, data) => apiClient.patch(`/product-categories/${id}`, data),
  delete: (id) => apiClient.delete(`/product-categories/${id}`),
};

