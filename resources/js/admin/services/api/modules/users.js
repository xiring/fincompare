/**
 * Users API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/users', { params }),
  show: (id) => apiClient.get(`/users/${id}`),
  create: (data) => apiClient.post('/users', data),
  update: (id, data) => apiClient.patch(`/users/${id}`, data),
  delete: (id) => apiClient.delete(`/users/${id}`),
};

