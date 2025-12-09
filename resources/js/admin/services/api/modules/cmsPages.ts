/**
 * CMS Pages API Module
 */

import apiClient from '../client';
import type { AxiosResponse } from 'axios';
import type { CmsPage, PaginatedResponse } from '../../../types/index';

export default {
  index: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<CmsPage> | CmsPage[]>> =>
    apiClient.get('/cms-pages', { params }),
  show: (id: number | string): Promise<AxiosResponse<{ data: CmsPage } | CmsPage>> =>
    apiClient.get(`/cms-pages/${id}`),
  create: (data: Partial<CmsPage>): Promise<AxiosResponse<{ data: CmsPage } | CmsPage>> =>
    apiClient.post('/cms-pages', data),
  update: (id: number | string, data: Partial<CmsPage>): Promise<AxiosResponse<{ data: CmsPage } | CmsPage>> =>
    apiClient.patch(`/cms-pages/${id}`, data),
  delete: (id: number | string): Promise<AxiosResponse<void>> => apiClient.delete(`/cms-pages/${id}`),
};

