/**
 * Validation utilities
 */

/**
 * Validate email format
 * @param {string} email
 * @returns {boolean}
 */
export function isValidEmail(email) {
  if (!email) return false;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

/**
 * Validate phone number
 * @param {string} phone
 * @returns {boolean}
 */
export function isValidPhone(phone) {
  if (!phone) return false;
  // Basic phone validation - accepts digits, spaces, dashes, parentheses, plus
  const phoneRegex = /^[\d\s\-()+]{7,}$/;
  return phoneRegex.test(phone);
}

/**
 * Validate required field
 * @param {*} value
 * @returns {boolean}
 */
export function isRequired(value) {
  if (value === null || value === undefined) return false;
  if (typeof value === 'string') return value.trim().length > 0;
  if (Array.isArray(value)) return value.length > 0;
  return true;
}

/**
 * Validate string length
 * @param {string} value
 * @param {number} min
 * @param {number} max
 * @returns {boolean}
 */
export function isValidLength(value, min = 0, max = Infinity) {
  if (!value) return min === 0;
  return value.length >= min && value.length <= max;
}

/**
 * Form validation helper
 * @param {object} rules
 * @param {object} data
 * @returns {object} { isValid: boolean, errors: object }
 */
export function validateForm(rules, data) {
  const errors = {};

  Object.keys(rules).forEach((field) => {
    const fieldRules = rules[field];
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

