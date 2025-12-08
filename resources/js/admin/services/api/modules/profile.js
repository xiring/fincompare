/**
 * Profile API Module
 * Note: Profile routes are under /api/profile, not /api/admin
 */

import axios from 'axios';
import { getCsrfToken } from '../client';

const getHeaders = () => {
  const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  };
  const token = getCsrfToken();
  if (token) {
    headers['X-CSRF-TOKEN'] = token;
  }
  return headers;
};

export default {
  show: () => axios.get('/api/profile', { headers: getHeaders() }),
  update: (data) => axios.patch('/api/profile', data, { headers: getHeaders() }),
  updatePassword: (data) => axios.put('/api/profile/password', data, { headers: getHeaders() }),
};

