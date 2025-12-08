/**
 * Settings API Module
 */

import apiClient from '../client';
import { toFormData } from '../utils';
import type { AxiosResponse } from 'axios';
import type { SiteSetting } from '../../../types/index';

export default {
  show: (): Promise<AxiosResponse<{ data: SiteSetting[] | Record<string, SiteSetting> } | SiteSetting[] | Record<string, SiteSetting>>> =>
    apiClient.get('/settings'),
  update: (data: Record<string, any>): Promise<AxiosResponse<{ data: SiteSetting[] | Record<string, SiteSetting> } | SiteSetting[] | Record<string, SiteSetting>>> => {
    const formData = toFormData(data, ['logo', 'favicon']);
    formData.append('_method', 'PATCH');
    return apiClient.post('/settings', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },
};

