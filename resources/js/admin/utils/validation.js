/**
 * Form Validation Utilities
 */

/**
 * Validates email format
 */
export const validateEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
};

/**
 * Validates required field
 */
export const validateRequired = (value) => {
  if (value === null || value === undefined) return false;
  if (typeof value === 'string') return value.trim().length > 0;
  if (Array.isArray(value)) return value.length > 0;
  return true;
};

/**
 * Validates minimum length
 */
export const validateMinLength = (value, min) => {
  if (!value) return false;
  return String(value).length >= min;
};

/**
 * Validates maximum length
 */
export const validateMaxLength = (value, max) => {
  if (!value) return true;
  return String(value).length <= max;
};

/**
 * Validates URL format
 */
export const validateUrl = (url) => {
  try {
    new URL(url);
    return true;
  } catch {
    return false;
  }
};

/**
 * Validates slug format
 */
export const validateSlug = (slug) => {
  const re = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
  return re.test(slug);
};

/**
 * Validates file type
 */
export const validateFileType = (file, allowedTypes) => {
  if (!file) return true;
  return allowedTypes.includes(file.type);
};

/**
 * Validates file size (in bytes)
 */
export const validateFileSize = (file, maxSize) => {
  if (!file) return true;
  return file.size <= maxSize;
};

/**
 * Extract validation errors from Laravel response
 */
export const extractValidationErrors = (error) => {
  if (error.response?.status === 422 && error.response.data?.errors) {
    return error.response.data.errors;
  }
  return {};
};

/**
 * Format validation error message
 */
export const formatValidationError = (errors, field) => {
  if (!errors || !errors[field]) return null;
  const fieldErrors = errors[field];
  return Array.isArray(fieldErrors) ? fieldErrors[0] : fieldErrors;
};

/**
 * Create validation rules object
 */
export const createValidator = (rules) => {
  return (data) => {
    const errors = {};

    Object.keys(rules).forEach(field => {
      const value = data[field];
      const fieldRules = rules[field];

      fieldRules.forEach(rule => {
        if (rule === 'required' && !validateRequired(value)) {
          errors[field] = `${field} is required`;
        } else if (rule.startsWith('min:') && value) {
          const min = parseInt(rule.split(':')[1]);
          if (!validateMinLength(value, min)) {
            errors[field] = `${field} must be at least ${min} characters`;
          }
        } else if (rule.startsWith('max:') && value) {
          const max = parseInt(rule.split(':')[1]);
          if (!validateMaxLength(value, max)) {
            errors[field] = `${field} must not exceed ${max} characters`;
          }
        } else if (rule === 'email' && value && !validateEmail(value)) {
          errors[field] = `${field} must be a valid email`;
        } else if (rule === 'url' && value && !validateUrl(value)) {
          errors[field] = `${field} must be a valid URL`;
        } else if (rule === 'slug' && value && !validateSlug(value)) {
          errors[field] = `${field} must be a valid slug`;
        }
      });
    });

    return Object.keys(errors).length > 0 ? errors : null;
  };
};

