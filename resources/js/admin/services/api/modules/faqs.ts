/**
 * FAQs API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { Faq, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Faq> | Faq[]>> =>
    apiClient.get('/faqs', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Faq } | Faq>> =>
    apiClient.get(`/faqs/${id}`),
  create: (data: Partial<Faq>): Promise<AxiosResponse<{ data: Faq } | Faq>> =>
    apiClient.post('/faqs', data),
  update: (id: number | string, data: Partial<Faq>): Promise<AxiosResponse<{ data: Faq } | Faq>> =>
    apiClient.patch(`/faqs/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/faqs/${id}`),
};

