/**
 * Product Categories API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';
import type { AxiosResponse } from 'axios';
import type { ProductCategory, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<ProductCategory> | ProductCategory[]>> =>
    apiClient.get('/product-categories', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: ProductCategory } | ProductCategory>> =>
    apiClient.get(`/product-categories/${id}`),
  create: (data: Partial<ProductCategory>): Promise<AxiosResponse<{ data: ProductCategory } | ProductCategory>> => {
    const formData = toFormData(data as Record<string, any>, ['image']);
    return apiClient.post('/product-categories', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  update: (id: number | string, data: Partial<ProductCategory>): Promise<AxiosResponse<{ data: ProductCategory } | ProductCategory>> => {
    const formData = toFormData(data as Record<string, any>, ['image']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/product-categories/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/product-categories/${id}`),
};

