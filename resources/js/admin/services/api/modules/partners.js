/**
 * Partners API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';

export default {
  index: (params = {}) => apiClient.get('/partners', { params }),
  show: (id) => apiClient.get(`/partners/${id}`),
  create: (data) => {
    const formData = toFormData(data, ['logo']);
    return apiClient.post('/partners', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  update: (id, data) => {
    const formData = toFormData(data, ['logo']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/partners/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  delete: (id) => apiClient.delete(`/partners/${id}`),
};

