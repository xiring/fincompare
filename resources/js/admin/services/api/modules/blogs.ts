/**
 * Blogs API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';
import type { AxiosResponse } from 'axios';
import type { BlogPost, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<BlogPost> | BlogPost[]>> =>
    apiClient.get('/blogs', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: BlogPost } | BlogPost>> =>
    apiClient.get(`/blogs/${id}`),
  create: (data: Partial<BlogPost>): Promise<AxiosResponse<{ data: BlogPost } | BlogPost>> => {
    const formData = toFormData(data as Record<string, any>, ['featured_image']);
    return apiClient.post('/blogs', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  update: (id: number | string, data: Partial<BlogPost>): Promise<AxiosResponse<{ data: BlogPost } | BlogPost>> => {
    const formData = toFormData(data as Record<string, any>, ['featured_image']);
    formData.append('_method', 'PATCH');
    return apiClient.post(`/blogs/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/blogs/${id}`),
};

