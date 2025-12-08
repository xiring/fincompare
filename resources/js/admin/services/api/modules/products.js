/**
 * Products API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';

export default {
  index: (params = {}) => apiClient.get('/products', { params }),
  show: (id) => apiClient.get(`/products/${id}`),
  create: (data) => {
    const formData = toFormData(data, ['image'], ['attributes']);
    return apiClient.post('/products', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  update: (id, data) => {
    const formData = toFormData(data, ['image'], ['attributes']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/products/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  delete: (id) => apiClient.delete(`/products/${id}`),
  duplicate: (id) => apiClient.post(`/products/${id}/duplicate`),
  import: (file, delimiter = ',', hasHeader = true) => {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('delimiter', delimiter);
    formData.append('has_header', hasHeader ? '1' : '0');
    return apiClient.post('/products/import', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
};

