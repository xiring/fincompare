/**
 * Groups API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Group, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Group> | Group[]>> =>
    apiClient.get('/groups', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Group } | Group>> =>
    apiClient.get(`/groups/${id}`),
  create: (data: Partial<Group>): Promise<AxiosResponse<{ data: Group } | Group>> =>
    apiClient.post('/groups', data),
  update: (id: number | string, data: Partial<Group>): Promise<AxiosResponse<{ data: Group } | Group>> =>
    apiClient.patch(`/groups/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/groups/${id}`),
};


