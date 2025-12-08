/**
 * CMS Pages API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/cms-pages', { params }),
  show: (id) => apiClient.get(`/cms-pages/${id}`),
  create: (data) => apiClient.post('/cms-pages', data),
  update: (id, data) => apiClient.patch(`/cms-pages/${id}`, data),
  delete: (id) => apiClient.delete(`/cms-pages/${id}`),
};

