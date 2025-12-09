/**
 * Profile API Module
 * Note: Profile routes are under /api/profile, not /api/admin
 */

import axios, { type AxiosResponse } from 'axios';
import { getCsrfToken } from '../client';
import type { User } from '../../../types/index';

const getHeaders = (): Record<string, string> => {
  const headers: Record<string, string> = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  };
  const token = getCsrfToken();
  if (token) {
    headers['X-CSRF-TOKEN'] = token;
  }
  return headers;
};

export default {
  show: (): Promise<AxiosResponse<{ data: User } | User>> =>
    axios.get('/api/profile', { headers: getHeaders() }),
  update: (data: Partial<User>): Promise<AxiosResponse<{ data: User } | User>> =>
    axios.patch('/api/profile', data, { headers: getHeaders() }),
  updatePassword: (data: { current_password: string; password: string; password_confirmation: string }): Promise<AxiosResponse<any>> =>
    axios.put('/api/profile/password', data, { headers: getHeaders() }),
};

