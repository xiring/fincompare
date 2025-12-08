/**
 * API Utility Functions
 * Helper functions for common API operations
 */

/**
 * Convert data object to FormData, handling files and nested objects
 */
export const toFormData = (data, fileFields = [], jsonFields = []) => {
  const formData = new FormData();

  Object.keys(data).forEach(key => {
    const value = data[key];

    // Skip null/undefined values
    if (value === null || value === undefined) {
      return;
    }

    // Handle file fields
    if (fileFields.includes(key) && value instanceof File) {
      formData.append(key, value);
    }
    // Handle JSON fields (like attributes) - explicitly specified
    else if (jsonFields.includes(key) && typeof value === 'object') {
      formData.append(key, JSON.stringify(value));
    }
    // Handle nested objects - stringify them
    else if (typeof value === 'object' && !(value instanceof File) && !(value instanceof Date) && !Array.isArray(value)) {
      formData.append(key, JSON.stringify(value));
    }
    // Handle arrays - stringify them
    else if (Array.isArray(value)) {
      formData.append(key, JSON.stringify(value));
    }
    // Handle regular values
    else {
      formData.append(key, value);
    }
  });

  return formData;
};

/**
 * Create a standard CRUD resource API module
 */
export const createResourceApi = (apiClient, resourcePath, options = {}) => {
  const { fileFields = [], customMethods = {} } = options;

  return {
    index: (params = {}) => apiClient.get(`/${resourcePath}`, { params }),
    show: (id) => apiClient.get(`/${resourcePath}/${id}`),
    create: (data) => {
      const formData = toFormData(data, fileFields);
      return apiClient.post(`/${resourcePath}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    update: (id, data) => {
      const formData = toFormData(data, fileFields);
      formData.append('_method', 'PATCH');
      return apiClient.post(`/${resourcePath}/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    },
    delete: (id) => apiClient.delete(`/${resourcePath}/${id}`),
    ...customMethods,
  };
};

