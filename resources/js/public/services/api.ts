/**
 * API Service Layer
 * Centralized API client with error handling and request/response interceptors
 */

import axios, { type AxiosInstance, type AxiosResponse, type InternalAxiosRequestConfig } from 'axios';
import type { PaginatedResponse } from '../../types/index';
import type { Product, BlogPost, Faq, SiteSettings } from '../types/index';

// Create axios instance with default config
const apiClient: AxiosInstance = axios.create({
  baseURL: '/api/public',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  timeout: 30000,
});

// Request interceptor
apiClient.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    // Add CSRF token if available
    const token = document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
    if (token && config.headers) {
      config.headers['X-CSRF-TOKEN'] = token.content;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor
apiClient.interceptors.response.use(
  (response: AxiosResponse) => response,
  (error: any) => {
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
  getHomeData: (): Promise<AxiosResponse<any>> => apiClient.get('/home'),

  // Site Settings
  getSiteSettings: (): Promise<AxiosResponse<{ data: SiteSettings } | SiteSettings>> =>
    apiClient.get('/site-settings'),

  // FAQs
  getFaqs: (): Promise<AxiosResponse<{ data: Faq[] } | Faq[]>> => apiClient.get('/faqs'),

  // Blog
  getBlogPosts: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<BlogPost> | BlogPost[]>> =>
    apiClient.get('/blog', { params }),
  getBlogPost: (slug: string): Promise<AxiosResponse<{ data: BlogPost } | BlogPost>> =>
    apiClient.get(`/blog/${slug}`),

  // Products
  getProducts: (params: Record<string, any> = {}): Promise<AxiosResponse<PaginatedResponse<Product> | Product[]>> =>
    apiClient.get('/products', { params }),
  getProduct: (idOrSlug: string | number): Promise<AxiosResponse<{ data: Product } | Product>> =>
    apiClient.get(`/products/${idOrSlug}`),
  getCategoryProducts: (
    slug: string,
    params: Record<string, any> = {}
  ): Promise<AxiosResponse<PaginatedResponse<Product> | Product[]>> =>
    apiClient.get(`/categories/${slug}`, { params }),
  getCompareData: (params: Record<string, any> = {}): Promise<AxiosResponse<any>> =>
    apiClient.get('/compare', { params }),
  toggleCompare: (data: { product_id: number }): Promise<AxiosResponse<any>> =>
    apiClient.post('/compare/toggle', data),
};

// Legacy web routes (non-API)
const webClient: AxiosInstance = axios.create({
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Add CSRF token to web requests
webClient.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    const token = document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
    if (token && config.headers) {
      config.headers['X-CSRF-TOKEN'] = token.content;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

export const webService = {
  submitContact: (data: Record<string, any>): Promise<AxiosResponse<any>> =>
    webClient.post('/contact', data),
  submitLead: (data: Record<string, any>): Promise<AxiosResponse<any>> =>
    webClient.post('/leads', data),
  toggleCompare: (data: { product_id: number }): Promise<AxiosResponse<any>> =>
    webClient.post('/compare/toggle', data),
};

export default apiClient;

