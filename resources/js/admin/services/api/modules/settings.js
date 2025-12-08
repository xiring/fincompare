/**
 * Settings API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';

export default {
  show: () => apiClient.get('/settings'),
  update: (data) => {
    const formData = toFormData(data, ['logo', 'favicon']);
    formData.append('_method', 'PATCH');
    return apiClient.post('/settings', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
};

