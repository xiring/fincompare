/**
 * FAQs API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/faqs', { params }),
  create: (data) => apiClient.post('/faqs', data),
  update: (id, data) => apiClient.patch(`/faqs/${id}`, data),
  delete: (id) => apiClient.delete(`/faqs/${id}`),
};

