/**
 * Base API Client
 * Centralized axios instance with interceptors
 */

import axios from 'axios';
import { requestManager } from '../../utils/requestManager';
import { performanceMonitor } from '../../utils/performanceMonitor';

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

// Enhanced request method with cancellation and deduplication
const originalRequest = apiClient.request.bind(apiClient);
apiClient.request = function(config) {
  const method = config.method?.toUpperCase() || 'GET';
  const url = config.url || '';
  const params = config.params || {};
  const data = config.data || null;

  // Generate request key (includes body for POST/PUT/DELETE)
  const requestKey = requestManager.getRequestKey(url, method, params, data);

  // Check if same request is already pending (deduplication)
  if (requestManager.isPending(requestKey)) {
    // Return the existing promise for all request types
    return requestManager.getPending(requestKey);
  }

  // Create cancel token (only for GET requests by default)
  // POST/PUT/DELETE requests are not cancelled to prevent data loss
  let source = null;
  if (method === 'GET' || !config.method) {
    const CancelToken = axios.CancelToken;
    source = CancelToken.source();
    config.cancelToken = source.token;
  }

  // Track request start time for performance monitoring
  const startTime = performance.now();

  // Make the request
  const requestPromise = originalRequest(config)
    .then(response => {
      // Record successful request
      const duration = Math.round(performance.now() - startTime);
      performanceMonitor.recordApiRequest(url, method, duration, response.status);
      return response;
    })
    .catch(error => {
      // Record failed request
      const duration = Math.round(performance.now() - startTime);
      const status = error.response?.status || 0;
      performanceMonitor.recordApiRequest(url, method, duration, status);
      throw error;
    });

  // Track the request (with cancel token only for GET)
  requestManager.addPending(requestKey, requestPromise, source);

  return requestPromise;
};

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
    // Don't treat cancelled requests as errors
    if (axios.isCancel(error)) {
      return Promise.reject(error);
    }

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

