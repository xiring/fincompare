/**
 * Activity API Module
 */

import apiClient from '../client';

export default {
  index: (params = {}) => apiClient.get('/activity', { params }),
};

