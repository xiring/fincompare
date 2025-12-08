/**
 * Blogs API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';

export default {
  index: (params = {}) => apiClient.get('/blogs', { params }),
  show: (id) => apiClient.get(`/blogs/${id}`),
  create: (data) => {
    const formData = toFormData(data, ['featured_image']);
    return apiClient.post('/blogs', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  update: (id, data) => {
    const formData = toFormData(data, ['featured_image']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/blogs/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
  delete: (id) => apiClient.delete(`/blogs/${id}`),
};

