/**
 * Uploads API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';

export default {
  wysiwyg: (file: File): Promise<AxiosResponse<{ url: string }>> => {
    const formData = new FormData();
    formData.append('image', file);
    return apiClient.post('/uploads/wysiwyg', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
};

