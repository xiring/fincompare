/**
 * Utility Helper Functions
 */

/**
 * Format number with locale string
 * @param {number} value
 * @param {string} mode - 'percent' | 'kplus' | 'plus' | 'default'
 * @returns {string}
 */
export function formatNumber(value, mode = 'default') {
  if (mode === 'percent') {
    return `${Math.round(value)}%`;
  }

  if (mode === 'kplus') {
    if (value >= 100000) {
      return `${Math.round(value / 1000)}k+`;
    }
    return `${Math.round(value).toLocaleString()}+`;
  }

  if (mode === 'plus') {
    return `${Math.round(value).toLocaleString()}+`;
  }

  return Math.round(value).toLocaleString();
}

/**
 * Get image URL with proper storage path handling
 * @param {string|null|undefined} imagePath
 * @param {string} fallback
 * @returns {string}
 */
export function getImageUrl(imagePath, fallback = 'https://placehold.co/400x300') {
  if (!imagePath) return fallback;
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  return `/storage/${imagePath}`;
}

/**
 * Get excerpt from HTML content
 * @param {string} content
 * @param {number} length
 * @returns {string}
 */
export function getExcerpt(content, length = 160) {
  if (!content) return '';
  const text = content.replace(/<[^>]*>/g, '').trim();
  return text.length > length ? `${text.substring(0, length)}...` : text;
}

/**
 * Format date string
 * @param {string|Date} dateString
 * @param {object} options
 * @returns {string}
 */
export function formatDate(dateString, options = {}) {
  if (!dateString) return '';
  const date = new Date(dateString);
  const defaultOptions = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    ...options,
  };
  return date.toLocaleDateString('en-US', defaultOptions);
}

/**
 * Debounce function
 * @param {Function} func
 * @param {number} wait
 * @returns {Function}
 */
export function debounce(func, wait = 300) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * Safe JSON parse with fallback
 * @param {string} str
 * @param {*} fallback
 * @returns {*}
 */
export function safeJsonParse(str, fallback = null) {
  try {
    return JSON.parse(str);
  } catch {
    return fallback;
  }
}

/**
 * Check if value is valid ID
 * @param {*} value
 * @returns {boolean}
 */
export function isValidId(value) {
  return value != null && !isNaN(value) && Number(value) > 0;
}

/**
 * Build query string from object
 * @param {object} params
 * @returns {string}
 */
export function buildQueryString(params) {
  const searchParams = new URLSearchParams();
  Object.entries(params).forEach(([key, value]) => {
    if (value !== null && value !== undefined && value !== '') {
      searchParams.append(key, String(value));
    }
  });
  return searchParams.toString();
}

/**
 * Copy text to clipboard
 * @param {string} text
 * @returns {Promise<boolean>}
 */
export async function copyToClipboard(text) {
  try {
    await navigator.clipboard.writeText(text);
    return true;
  } catch (err) {
    console.error('Failed to copy to clipboard:', err);
    return false;
  }
}

