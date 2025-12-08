/**
 * Base API Client
 * Centralized axios instance with interceptors
 */

import axios from 'axios';

// Helper to get CSRF token
export const getCsrfToken = () => {
  const token = document.head.querySelector('meta[name="csrf-token"]');
  return token ? token.content : null;
};

// Create axios instance with default config
const apiClient = axios.create({
  baseURL: '/api/admin',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 30000,
});

// Request interceptor
apiClient.interceptors.request.use(
  (config) => {
    // Add CSRF token if available
    const token = getCsrfToken();
    if (token) {
      config.headers['X-CSRF-TOKEN'] = token;
    }

    // Add Accept header for JSON responses
    config.headers['Accept'] = 'application/json';

    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    // Handle common errors
    if (error.response?.status === 401) {
      // Redirect to login on unauthorized
      window.location.href = '/login';
    } else if (error.response?.status === 403) {
      console.warn('Forbidden: Insufficient permissions');
    } else if (error.response?.status === 404) {
      console.warn('Resource not found');
    } else if (error.response?.status >= 500) {
      console.error('Server error:', error.response?.data);
    }
    return Promise.reject(error);
  }
);

export default apiClient;

