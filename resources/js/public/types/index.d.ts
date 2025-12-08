/**
 * Public-facing Type Definitions
 */

import type { BaseEntity } from '../../types';

// Re-export common types
export type { Product, ProductCategory, Partner, BlogPost, Faq, CmsPage } from '../../admin/types';

// Site Settings
export interface SiteSettings {
  site_name?: string;
  site_description?: string;
  site_logo?: string;
  site_favicon?: string;
  contact_email?: string;
  contact_phone?: string;
  social_facebook?: string;
  social_twitter?: string;
  social_instagram?: string;
  [key: string]: any;
}

// Toast types
export interface Toast {
  id: string;
  message: string;
  type: 'success' | 'error' | 'warning' | 'info';
  duration?: number;
}

// Compare types
export interface CompareItem {
  product_id: number;
  product?: Product;
}

// Form submission types
export interface FormSubmissionData {
  [key: string]: any;
}

