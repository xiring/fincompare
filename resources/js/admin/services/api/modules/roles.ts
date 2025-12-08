/**
 * Roles API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Role, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Role> | Role[]>> =>
    apiClient.get('/roles', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Role } | Role>> =>
    apiClient.get(`/roles/${id}`),
  create: (data: Partial<Role>): Promise<AxiosResponse<{ data: Role } | Role>> =>
    apiClient.post('/roles', data),
  update: (id: number | string, data: Partial<Role>): Promise<AxiosResponse<{ data: Role } | Role>> =>
    apiClient.patch(`/roles/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/roles/${id}`),
};

