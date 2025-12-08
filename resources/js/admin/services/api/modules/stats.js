/**
 * Stats API Module
 * Handles dashboard statistics endpoints
 */

import apiClient from '../client';

export default {
  /**
   * Get dashboard statistics
   */
  index: () => apiClient.get('/stats'),
};

