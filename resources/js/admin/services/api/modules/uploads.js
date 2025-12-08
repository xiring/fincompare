/**
 * Uploads API Module
 */

import apiClient from '../client';

export default {
  wysiwyg: (file) => {
    const formData = new FormData();
    formData.append('image', file);
    return apiClient.post('/uploads/wysiwyg', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  },
};

