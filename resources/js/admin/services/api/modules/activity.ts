/**
 * Activity API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { ActivityLog, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<ActivityLog> | ActivityLog[]>> =>
    apiClient.get('/activity', { params }),
};

