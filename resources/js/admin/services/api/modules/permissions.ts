/**
 * Permissions API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Permission, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Permission> | Permission[]>> =>
    apiClient.get('/permissions', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Permission } | Permission>> =>
    apiClient.get(`/permissions/${id}`),
  create: (data: Partial<Permission>): Promise<AxiosResponse<{ data: Permission } | Permission>> =>
    apiClient.post('/permissions', data),
  update: (id: number | string, data: Partial<Permission>): Promise<AxiosResponse<{ data: Permission } | Permission>> =>
    apiClient.patch(`/permissions/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/permissions/${id}`),
};

