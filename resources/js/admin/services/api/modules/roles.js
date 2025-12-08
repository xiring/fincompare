/**
 * Roles API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/roles', { params }),
  show: (id) => apiClient.get(`/roles/${id}`),
  create: (data) => apiClient.post('/roles', data),
  update: (id, data) => apiClient.patch(`/roles/${id}`, data),
  delete: (id) => apiClient.delete(`/roles/${id}`),
};

