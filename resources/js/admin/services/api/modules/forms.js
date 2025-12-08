/**
 * Forms API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/forms', { params }),
  show: (id) => apiClient.get(`/forms/${id}`),
  create: (data) => apiClient.post('/forms', data),
  update: (id, data) => apiClient.patch(`/forms/${id}`, data),
  delete: (id) => apiClient.delete(`/forms/${id}`),
  duplicate: (id) => apiClient.post(`/forms/${id}/duplicate`),
};

