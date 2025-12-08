/**
 * Form Validation Utilities
 */

import type { Ref } from 'vue';

export type FormErrors = Record<string, string | string[]>;

/**
 * Validates email format
 */
export const validateEmail = (email: string): boolean => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
};

/**
 * Validates required field
 */
export const validateRequired = (value: any): boolean => {
  if (value === null || value === undefined) return false;
  if (typeof value === 'string') return value.trim().length > 0;
  if (Array.isArray(value)) return value.length > 0;
  return true;
};

/**
 * Validates minimum length
 */
export const validateMinLength = (value: any, min: number): boolean => {
  if (!value) return false;
  return String(value).length >= min;
};

/**
 * Validates maximum length
 */
export const validateMaxLength = (value: any, max: number): boolean => {
  if (!value) return true;
  return String(value).length <= max;
};

/**
 * Validates URL format
 */
export const validateUrl = (url: string): boolean => {
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
export const validateSlug = (slug: string): boolean => {
  const re = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
  return re.test(slug);
};

/**
 * Validates file type
 */
export const validateFileType = (file: File | null, allowedTypes: string[]): boolean => {
  if (!file) return true;
  return allowedTypes.includes(file.type);
};

/**
 * Validates file size (in bytes)
 */
export const validateFileSize = (file: File | null, maxSize: number): boolean => {
  if (!file) return true;
  return file.size <= maxSize;
};

/**
 * Extract validation errors from Laravel response
 */
export const extractValidationErrors = (error: any): Record<string, string[]> => {
  if (error.response?.status === 422 && error.response.data?.errors) {
    return error.response.data.errors;
  }
  return {};
};

/**
 * Format validation error message
 */
export const formatValidationError = (
  errors: Record<string, string[]> | null,
  field: string
): string | null => {
  if (!errors || !errors[field]) return null;
  const fieldErrors = errors[field];
  if (!fieldErrors) return null;
  const error = Array.isArray(fieldErrors) ? fieldErrors[0] : String(fieldErrors);
  return error || null;
};

/**
 * Get error message for a field from FormErrors
 */
export const getError = (errors: Ref<FormErrors> | FormErrors, field: string): string | undefined => {
  const errorsObj = 'value' in errors ? errors.value : errors;
  const fieldErrors = errorsObj[field as keyof typeof errorsObj];
  if (!fieldErrors) return undefined;
  return Array.isArray(fieldErrors) ? fieldErrors[0] : (typeof fieldErrors === 'string' ? fieldErrors : undefined);
};

/**
 * Create validation rules object
 */
export const createValidator = (rules: Record<string, string[]>) => {
  return (data: Record<string, any>): Record<string, string> | null => {
    const errors: Record<string, string> = {};

    Object.keys(rules).forEach((field) => {
      const value = data[field];
      const fieldRules = rules[field];
      if (!fieldRules) return;

      fieldRules.forEach((rule) => {
        if (rule === 'required' && !validateRequired(value)) {
          errors[field] = `${field} is required`;
        } else if (rule.startsWith('min:') && value) {
          const parts = rule.split(':');
          if (parts.length < 2) return;
          const min = parseInt(parts[1] || '0');
          if (isNaN(min)) return;
          if (!validateMinLength(String(value), min)) {
            errors[field] = `${field} must be at least ${min} characters`;
          }
        } else if (rule.startsWith('max:') && value) {
          const max = parseInt(rule.split(':')[1] || '0');
          if (!validateMaxLength(String(value), max)) {
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

