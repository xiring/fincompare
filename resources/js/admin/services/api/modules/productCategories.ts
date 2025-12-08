/**
 * Product Categories API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { ProductCategory, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<ProductCategory> | ProductCategory[]>> =>
    apiClient.get('/product-categories', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: ProductCategory } | ProductCategory>> =>
    apiClient.get(`/product-categories/${id}`),
  create: (data: Partial<ProductCategory>): Promise<AxiosResponse<{ data: ProductCategory } | ProductCategory>> =>
    apiClient.post('/product-categories', data),
  update: (id: number | string, data: Partial<ProductCategory>): Promise<AxiosResponse<{ data: ProductCategory } | ProductCategory>> =>
    apiClient.patch(`/product-categories/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/product-categories/${id}`),
};

