/**
 * Partners API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';
import type { AxiosResponse } from 'axios';
import type { Partner, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Partner> | Partner[]>> =>
    apiClient.get('/partners', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Partner } | Partner>> =>
    apiClient.get(`/partners/${id}`),
  create: (data: Partial<Partner>): Promise<AxiosResponse<{ data: Partner } | Partner>> => {
    const formData = toFormData(data as Record<string, any>, ['logo']);
    return apiClient.post('/partners', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  update: (id: number | string, data: Partial<Partner>): Promise<AxiosResponse<{ data: Partner } | Partner>> => {
    const formData = toFormData(data as Record<string, any>, ['logo']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/partners/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/partners/${id}`),
};

