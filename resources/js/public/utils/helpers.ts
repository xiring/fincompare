/**
 * Utility Helper Functions
 */

/**
 * Format number with locale string
 */
export function formatNumber(value: number, mode: 'percent' | 'kplus' | 'plus' | 'default' = 'default'): string {
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
 */
export function getImageUrl(imagePath: string | null | undefined, fallback: string = 'https://placehold.co/400x300'): string {
  if (!imagePath) return fallback;
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  return `/storage/${imagePath}`;
}

/**
 * Get excerpt from HTML content
 */
export function getExcerpt(content: string | null | undefined, length: number = 160): string {
  if (!content) return '';
  const text = content.replace(/<[^>]*>/g, '').trim();
  return text.length > length ? `${text.substring(0, length)}...` : text;
}

/**
 * Format date string
 */
export function formatDate(dateString: string | Date | null | undefined, options: Intl.DateTimeFormatOptions = {}): string {
  if (!dateString) return '';
  const date = new Date(dateString);
  const defaultOptions: Intl.DateTimeFormatOptions = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    ...options,
  };
  return date.toLocaleDateString('en-US', defaultOptions);
}

/**
 * Debounce function
 */
export function debounce<T extends (...args: any[]) => any>(func: T, wait: number = 300): (...args: Parameters<T>) => void {
  let timeout: ReturnType<typeof setTimeout>;
  return function executedFunction(...args: Parameters<T>) {
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
 */
export function safeJsonParse<T = any>(str: string, fallback: T | null = null): T | null {
  try {
    return JSON.parse(str) as T;
  } catch {
    return fallback;
  }
}

/**
 * Check if value is valid ID
 */
export function isValidId(value: any): boolean {
  return value != null && !isNaN(value) && Number(value) > 0;
}

/**
 * Build query string from object
 */
export function buildQueryString(params: Record<string, any>): string {
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
 */
export async function copyToClipboard(text: string): Promise<boolean> {
  try {
    await navigator.clipboard.writeText(text);
    return true;
  } catch (err) {
    console.error('Failed to copy to clipboard:', err);
    return false;
  }
}

/**
 * Get logo URL with proper storage path handling
 */
export function getLogoUrl(logoPath: string | null | undefined): string | null {
  if (!logoPath) return null;
  // If logo is already a full URL, return as is
  if (logoPath.startsWith('http://') || logoPath.startsWith('https://')) {
    return logoPath;
  }
  // If logo starts with /storage, return as is
  if (logoPath.startsWith('/storage')) {
    return logoPath;
  }
  // Otherwise, prepend /storage/
  return `/storage/${logoPath}`;
}

