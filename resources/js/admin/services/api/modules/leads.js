/**
 * Leads API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/leads', { params }),
  show: (id) => apiClient.get(`/leads/${id}`),
  update: (id, data) => apiClient.patch(`/leads/${id}`, data),
  export: () => apiClient.get('/leads-export', { responseType: 'blob' }),
};

