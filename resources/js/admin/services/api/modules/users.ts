/**
 * Users API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { User, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<User> | User[]>> =>
    apiClient.get('/users', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: User } | User>> =>
    apiClient.get(`/users/${id}`),
  create: (data: Partial<User>): Promise<AxiosResponse<{ data: User } | User>> =>
    apiClient.post('/users', data),
  update: (id: number | string, data: Partial<User>): Promise<AxiosResponse<{ data: User } | User>> =>
    apiClient.patch(`/users/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/users/${id}`),
};

