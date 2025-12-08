/**
 * Permissions API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/permissions', { params }),
  show: (id) => apiClient.get(`/permissions/${id}`),
  create: (data) => apiClient.post('/permissions', data),
  update: (id, data) => apiClient.patch(`/permissions/${id}`, data),
  delete: (id) => apiClient.delete(`/permissions/${id}`),
};

