/**
 * Validation utilities
 */

/**
 * Validate email format
 */
export function isValidEmail(email: string | null | undefined): boolean {
  if (!email) return false;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

/**
 * Validate phone number
 */
export function isValidPhone(phone: string | null | undefined): boolean {
  if (!phone) return false;
  // Basic phone validation - accepts digits, spaces, dashes, parentheses, plus
  const phoneRegex = /^[\d\s\-()+]{7,}$/;
  return phoneRegex.test(phone);
}

/**
 * Validate required field
 */
export function isRequired(value: any): boolean {
  if (value === null || value === undefined) return false;
  if (typeof value === 'string') return value.trim().length > 0;
  if (Array.isArray(value)) return value.length > 0;
  return true;
}

/**
 * Validate string length
 */
export function isValidLength(value: string | null | undefined, min: number = 0, max: number = Infinity): boolean {
  if (!value) return min === 0;
  return value.length >= min && value.length <= max;
}

interface ValidationRule {
  required?: boolean;
  requiredMessage?: string;
  email?: boolean;
  emailMessage?: string;
  phone?: boolean;
  phoneMessage?: string;
  minLength?: number;
  minLengthMessage?: string;
  maxLength?: number;
  maxLengthMessage?: string;
}

interface ValidationRules {
  [key: string]: ValidationRule;
}

interface ValidationResult {
  isValid: boolean;
  errors: Record<string, string>;
}

/**
 * Form validation helper
 */
export function validateForm(rules: ValidationRules, data: Record<string, any>): ValidationResult {
  const errors: Record<string, string> = {};

  Object.keys(rules).forEach((field) => {
    const fieldRules = rules[field];
    if (!fieldRules) return;

    const value = data[field];

    if (fieldRules.required && !isRequired(value)) {
      errors[field] = fieldRules.requiredMessage || `${field} is required`;
      return;
    }

    if (value && fieldRules.email && !isValidEmail(value)) {
      errors[field] = fieldRules.emailMessage || 'Invalid email format';
      return;
    }

    if (value && fieldRules.phone && !isValidPhone(value)) {
      errors[field] = fieldRules.phoneMessage || 'Invalid phone number';
      return;
    }

    if (value && fieldRules.minLength && !isValidLength(value, fieldRules.minLength)) {
      errors[field] = fieldRules.minLengthMessage || `Minimum length is ${fieldRules.minLength}`;
      return;
    }

    if (value && fieldRules.maxLength && !isValidLength(value, 0, fieldRules.maxLength)) {
      errors[field] = fieldRules.maxLengthMessage || `Maximum length is ${fieldRules.maxLength}`;
      return;
    }
  });

  return {
    isValid: Object.keys(errors).length === 0,
    errors,
  };
}

