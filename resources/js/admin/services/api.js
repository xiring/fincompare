/**
 * Admin API Service Layer
 * Centralized API client for admin endpoints with error handling
 */

import axios from 'axios';

// Helper to get CSRF token
const getCsrfToken = () => {
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
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
      config.headers['X-CSRF-TOKEN'] = token.content;
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

/**
 * Admin API Service Methods
 */
export const adminApi = {
  // Products
  products: {
    index: (params = {}) => apiClient.get('/products', { params }),
    show: (id) => apiClient.get(`/products/${id}`),
    create: (data) => {
      const formData = new FormData();
      Object.keys(data).forEach(key => {
        if (key === 'image' && data[key] instanceof File) {
          formData.append('image', data[key]);
        } else if (key === 'attributes' && typeof data[key] === 'object') {
          formData.append('attributes', JSON.stringify(data[key]));
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return apiClient.post('/products', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    update: (id, data) => {
      const formData = new FormData();
      formData.append('_method', 'PATCH');
      Object.keys(data).forEach(key => {
        if (key === 'image' && data[key] instanceof File) {
          formData.append('image', data[key]);
        } else if (key === 'attributes' && typeof data[key] === 'object') {
          formData.append('attributes', JSON.stringify(data[key]));
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return apiClient.post(`/products/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    delete: (id) => apiClient.delete(`/products/${id}`),
    duplicate: (id) => apiClient.post(`/products/${id}/duplicate`),
    import: (file) => {
      const formData = new FormData();
      formData.append('file', file);
      return apiClient.post('/products/import', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
  },

  // Partners
  partners: {
    index: (params = {}) => apiClient.get('/partners', { params }),
    show: (id) => apiClient.get(`/partners/${id}`),
    create: (data) => {
      const formData = new FormData();
      Object.keys(data).forEach(key => {
        if (key === 'logo' && data[key] instanceof File) {
          formData.append('logo', data[key]);
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return apiClient.post('/partners', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    update: (id, data) => {
      const formData = new FormData();
      formData.append('_method', 'PATCH');
      Object.keys(data).forEach(key => {
        if (key === 'logo' && data[key] instanceof File) {
          formData.append('logo', data[key]);
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return apiClient.post(`/partners/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    delete: (id) => apiClient.delete(`/partners/${id}`),
  },

  // Product Categories
  productCategories: {
    index: (params = {}) => apiClient.get('/product-categories', { params }),
    show: (id) => apiClient.get(`/product-categories/${id}`),
    create: (data) => apiClient.post('/product-categories', data),
    update: (id, data) => apiClient.patch(`/product-categories/${id}`, data),
    delete: (id) => apiClient.delete(`/product-categories/${id}`),
  },

  // Attributes
  attributes: {
    index: (params = {}) => apiClient.get('/attributes', { params }),
    create: (data) => apiClient.post('/attributes', data),
    update: (id, data) => apiClient.patch(`/attributes/${id}`, data),
    delete: (id) => apiClient.delete(`/attributes/${id}`),
    byCategory: (categoryId) => apiClient.get(`/attributes/by-category/${categoryId}`),
  },

  // Users
  users: {
    index: (params = {}) => apiClient.get('/users', { params }),
    show: (id) => apiClient.get(`/users/${id}`),
    create: (data) => apiClient.post('/users', data),
    update: (id, data) => apiClient.patch(`/users/${id}`, data),
    delete: (id) => apiClient.delete(`/users/${id}`),
  },

  // Roles
  roles: {
    index: (params = {}) => apiClient.get('/roles', { params }),
    show: (id) => apiClient.get(`/roles/${id}`),
    create: (data) => apiClient.post('/roles', data),
    update: (id, data) => apiClient.patch(`/roles/${id}`, data),
    delete: (id) => apiClient.delete(`/roles/${id}`),
  },

  // Permissions
  permissions: {
    index: (params = {}) => apiClient.get('/permissions', { params }),
    create: (data) => apiClient.post('/permissions', data),
    update: (id, data) => apiClient.patch(`/permissions/${id}`, data),
    delete: (id) => apiClient.delete(`/permissions/${id}`),
  },

  // Leads
  leads: {
    index: (params = {}) => apiClient.get('/leads', { params }),
    show: (id) => apiClient.get(`/leads/${id}`),
    update: (id, data) => apiClient.patch(`/leads/${id}`, data),
    export: () => apiClient.get('/leads-export', { responseType: 'blob' }),
  },

  // Forms
  forms: {
    index: (params = {}) => apiClient.get('/forms', { params }),
    show: (id) => apiClient.get(`/forms/${id}`),
    create: (data) => apiClient.post('/forms', data),
    update: (id, data) => apiClient.patch(`/forms/${id}`, data),
    delete: (id) => apiClient.delete(`/forms/${id}`),
    duplicate: (id) => apiClient.post(`/forms/${id}/duplicate`),
  },

  // Blogs
  blogs: {
    index: (params = {}) => apiClient.get('/blogs', { params }),
    show: (id) => apiClient.get(`/blogs/${id}`),
    create: (data) => {
      const formData = new FormData();
      Object.keys(data).forEach(key => {
        if (key === 'featured_image' && data[key] instanceof File) {
          formData.append('featured_image', data[key]);
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return apiClient.post('/blogs', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    update: (id, data) => {
      const formData = new FormData();
      formData.append('_method', 'PATCH');
      Object.keys(data).forEach(key => {
        if (key === 'featured_image' && data[key] instanceof File) {
          formData.append('featured_image', data[key]);
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      return apiClient.post(`/blogs/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    delete: (id) => apiClient.delete(`/blogs/${id}`),
  },

  // CMS Pages
  cmsPages: {
    index: (params = {}) => apiClient.get('/cms-pages', { params }),
    show: (id) => apiClient.get(`/cms-pages/${id}`),
    create: (data) => apiClient.post('/cms-pages', data),
    update: (id, data) => apiClient.patch(`/cms-pages/${id}`, data),
    delete: (id) => apiClient.delete(`/cms-pages/${id}`),
  },

  // FAQs
  faqs: {
    index: (params = {}) => apiClient.get('/faqs', { params }),
    create: (data) => apiClient.post('/faqs', data),
    update: (id, data) => apiClient.patch(`/faqs/${id}`, data),
    delete: (id) => apiClient.delete(`/faqs/${id}`),
  },

  // Settings
  settings: {
    show: () => apiClient.get('/settings'),
    update: (data) => {
      const formData = new FormData();
      Object.keys(data).forEach(key => {
        if ((key === 'logo' || key === 'favicon') && data[key] instanceof File) {
          formData.append(key, data[key]);
        } else if (data[key] !== null && data[key] !== undefined) {
          formData.append(key, data[key]);
        }
      });
      formData.append('_method', 'PATCH');
      return apiClient.post('/settings', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
  },

  // Activity
  activity: {
    index: (params = {}) => apiClient.get('/activity', { params }),
  },

  // Profile
  profile: {
    show: () => {
      // Profile routes are under /api/profile, not /api/admin
      const headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      };
      const token = getCsrfToken();
      if (token) {
        headers['X-CSRF-TOKEN'] = token;
      }
      return axios.get('/api/profile', { headers });
    },
    update: (data) => {
      const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      };
      const token = getCsrfToken();
      if (token) {
        headers['X-CSRF-TOKEN'] = token;
      }
      return axios.patch('/api/profile', data, { headers });
    },
    updatePassword: (data) => {
      const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      };
      const token = getCsrfToken();
      if (token) {
        headers['X-CSRF-TOKEN'] = token;
      }
      return axios.put('/api/profile/password', data, { headers });
    },
  },

  // Uploads
  uploads: {
    wysiwyg: (file) => {
      const formData = new FormData();
      formData.append('image', file);
      return apiClient.post('/uploads/wysiwyg', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
  },
};

export default apiClient;

