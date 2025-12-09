/**
 * Leads API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Lead, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Lead> | Lead[]>> =>
    apiClient.get('/leads', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Lead } | Lead>> =>
    apiClient.get(`/leads/${id}`),
  update: (id: number | string, data: Partial<Lead>): Promise<AxiosResponse<{ data: Lead } | Lead>> =>
    apiClient.patch(`/leads/${id}`, data),
  export: (): Promise<AxiosResponse<Blob>> => apiClient.get('/leads-export', { responseType: 'blob' }),
};

