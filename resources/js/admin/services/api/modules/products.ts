/**
 * Products API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';
import type { AxiosResponse } from 'axios';
import type { Product, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Product> | Product[]>> =>
    apiClient.get('/products', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: Product } | Product>> =>
    apiClient.get(`/products/${id}`),
  create: (data: Partial<Product>): Promise<AxiosResponse<{ data: Product } | Product>> => {
    const formData = toFormData(data as Record<string, any>, ['image'], ['attributes']);
    return apiClient.post('/products', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  update: (id: number | string, data: Partial<Product>): Promise<AxiosResponse<{ data: Product } | Product>> => {
    const formData = toFormData(data as Record<string, any>, ['image'], ['attributes']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/products/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/products/${id}`),
  duplicate: (id: number | string): Promise<AxiosResponse<{ data: Product } | Product>> =>
    apiClient.post(`/products/${id}/duplicate`),
  import: (
    file: File,
    delimiter: string = ',',
    hasHeader: boolean = true
  ): Promise<AxiosResponse<any>> => {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('delimiter', delimiter);
    formData.append('has_header', hasHeader ? '1' : '0');
    return apiClient.post('/products/import', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
};

