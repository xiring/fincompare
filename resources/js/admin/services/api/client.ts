/**
 * Base API Client
 * Centralized axios instance with interceptors
 */

import axios, { type AxiosInstance, type AxiosRequestConfig, type AxiosResponse, type InternalAxiosRequestConfig } from 'axios';
import { requestManager } from '../../utils/requestManager';
import { performanceMonitor } from '../../utils/performanceMonitor';

// Helper to get CSRF token
export const getCsrfToken = (): string | null => {
  const token = document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
  return token ? token.content : null;
};

// Create axios instance with default config
const apiClient: AxiosInstance = axios.create({
  baseURL: '/api/admin',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  timeout: 30000,
});

// Enhanced request method with cancellation and deduplication
const originalRequest = apiClient.request.bind(apiClient);
(apiClient as any).request = function <T = any, D = any>(
  config: AxiosRequestConfig<D>
): Promise<AxiosResponse<T, D>> {
  const method = (config.method?.toUpperCase() || 'GET') as string;
  const url = config.url || '';
  const params = config.params || {};
  const data = config.data || null;

  // Generate request key (includes body for POST/PUT/DELETE)
  const requestKey = requestManager.getRequestKey(url, method, params, data);

  // Check if same request is already pending (deduplication)
  if (requestManager.isPending(requestKey)) {
    // Return the existing promise for all request types
    return requestManager.getPending(requestKey) as Promise<AxiosResponse<T, D>>;
  }

  // Create cancel token (only for GET requests by default)
  // POST/PUT/DELETE requests are not cancelled to prevent data loss
  let source: ReturnType<typeof axios.CancelToken.source> | null = null;
  if (method === 'GET' || !config.method) {
    source = axios.CancelToken.source();
    config.cancelToken = source.token;
  }

  // Track request start time for performance monitoring
  const startTime = performance.now();

  // Make the request
  const requestPromise = originalRequest<T, D>(config)
    .then((response) => {
      // Record successful request
      const duration = Math.round(performance.now() - startTime);
      const status = (response as AxiosResponse<any, any>).status;
      performanceMonitor.recordApiRequest(url, method, duration, status);
      return response;
    })
    .catch((error) => {
      // Record failed request
      const duration = Math.round(performance.now() - startTime);
      const status = (error as any)?.response?.status || 0;
      performanceMonitor.recordApiRequest(url, method, duration, status);
      throw error;
    });

  // Track the request (with cancel token only for GET)
  requestManager.addPending(requestKey, requestPromise, source);

  return requestPromise as Promise<AxiosResponse<T, D>>;
};

// Request interceptor
apiClient.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    // Add CSRF token if available
    const token = getCsrfToken();
    if (token && config.headers) {
      config.headers['X-CSRF-TOKEN'] = token;
    }

    // Add Accept header for JSON responses
    if (config.headers) {
      config.headers['Accept'] = 'application/json';
    }

    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor
apiClient.interceptors.response.use(
  (response: AxiosResponse) => response,
  (error: any) => {
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

