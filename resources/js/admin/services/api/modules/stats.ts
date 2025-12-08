/**
 * Stats API Module
 * Handles dashboard statistics endpoints
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { DashboardStats } from '../../../types/index';

export default {
  /**
   * Get dashboard statistics
   */
  index: (): Promise<AxiosResponse<{ data: DashboardStats } | DashboardStats>> => apiClient.get('/stats'),
};

