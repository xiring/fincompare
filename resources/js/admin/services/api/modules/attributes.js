/**
 * Attributes API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/attributes', { params }),
  create: (data) => apiClient.post('/attributes', data),
  update: (id, data) => apiClient.patch(`/attributes/${id}`, data),
  delete: (id) => apiClient.delete(`/attributes/${id}`),
  byCategory: (categoryId) => apiClient.get(`/attributes/by-category/${categoryId}`),
};

