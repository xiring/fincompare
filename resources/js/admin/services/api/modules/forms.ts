/**
 * Forms API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Form, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Form> | Form[]>> =>
    apiClient.get('/forms', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Form } | Form>> =>
    apiClient.get(`/forms/${id}`),
  create: (data: Partial<Form>): Promise<AxiosResponse<{ data: Form } | Form>> =>
    apiClient.post('/forms', data),
  update: (id: number | string, data: Partial<Form>): Promise<AxiosResponse<{ data: Form } | Form>> =>
    apiClient.patch(`/forms/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/forms/${id}`),
  duplicate: (id: number | string): Promise<AxiosResponse<{ data: Form } | Form>> =>
    apiClient.post(`/forms/${id}/duplicate`),
};

