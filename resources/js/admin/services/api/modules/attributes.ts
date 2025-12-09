/**
 * Attributes API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Attribute, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Attribute> | Attribute[]>> =>
    apiClient.get('/attributes', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Attribute } | Attribute>> =>
    apiClient.get(`/attributes/${id}/edit`),
  edit: (id: number | string): Promise<AxiosResponse<{ data: Attribute } | Attribute>> =>
    apiClient.get(`/attributes/${id}/edit`),
  create: (data: Partial<Attribute>): Promise<AxiosResponse<{ data: Attribute } | Attribute>> =>
    apiClient.post('/attributes', data),
  update: (id: number | string, data: Partial<Attribute>): Promise<AxiosResponse<{ data: Attribute } | Attribute>> =>
    apiClient.patch(`/attributes/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/attributes/${id}`),
  byCategory: (categoryId: number | string): Promise<AxiosResponse<{ data: Attribute[] } | Attribute[]>> =>
    apiClient.get(`/attributes/by-category/${categoryId}`),
};

