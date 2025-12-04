/**
 * API Service Layer
 * Centralized API client with error handling and request/response interceptors
 */

import axios from 'axios';

// Create axios instance with default config
const apiClient = axios.create({
  baseURL: '/api/public',
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
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
      config.headers['X-CSRF-TOKEN'] = token.content;
    }
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
      // Handle unauthorized
      console.warn('Unauthorized request');
    } else if (error.response?.status === 404) {
      // Handle not found
      console.warn('Resource not found');
    } else if (error.response?.status >= 500) {
      // Handle server errors
      console.error('Server error:', error.response?.data);
    }
    return Promise.reject(error);
  }
);

/**
 * API Service Methods
 */
export const apiService = {
  // Home
  getHomeData: () => apiClient.get('/home'),

  // Site Settings
  getSiteSettings: () => apiClient.get('/site-settings'),

  // FAQs
  getFaqs: () => apiClient.get('/faqs'),

  // Blog
  getBlogPosts: (params = {}) => apiClient.get('/blog', { params }),
  getBlogPost: (slug) => apiClient.get(`/blog/${slug}`),

  // Products
  getProducts: (params = {}) => apiClient.get('/products', { params }),
  getProduct: (idOrSlug) => apiClient.get(`/products/${idOrSlug}`),
  getCategoryProducts: (slug, params = {}) => apiClient.get(`/categories/${slug}`, { params }),
  getCompareData: (params = {}) => apiClient.get('/compare', { params }),
  toggleCompare: (data) => apiClient.post('/compare/toggle', data),
};

// Legacy web routes (non-API)
const webClient = axios.create({
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add CSRF token to web requests
webClient.interceptors.request.use(
  (config) => {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
      config.headers['X-CSRF-TOKEN'] = token.content;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

export const webService = {
  submitContact: (data) => webClient.post('/contact', data),
  submitLead: (data) => webClient.post('/leads', data),
  toggleCompare: (data) => webClient.post('/compare/toggle', data),
};

export default apiClient;

