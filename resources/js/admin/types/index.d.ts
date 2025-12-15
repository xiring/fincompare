/**
 * Admin-specific Type Definitions
 */

import type { BaseEntity, PaginatedResponse } from '../../types';

// User types
export interface User extends BaseEntity {
  name: string;
  email: string;
  email_verified_at?: string;
  roles?: Role[];
  permissions?: Permission[];
}

// Role types
export interface Role extends BaseEntity {
  name: string;
  guard_name: string;
  permissions?: Permission[];
}

// Permission types
export interface Permission extends BaseEntity {
  name: string;
  guard_name: string;
}

// Product types
export interface Product extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  image?: string;
  image_url?: string;
  partner_id?: number;
  partner?: Partner;
  category_id?: number;
  category?: ProductCategory;
  product_category?: ProductCategory; // Alias for category
  attributeValues?: ProductAttributeValue[];
  is_active?: boolean;
  is_featured?: boolean;
  featured?: boolean; // Alias for is_featured
  status?: 'active' | 'inactive' | 'published' | 'draft'; // Status field
  eligibility?: string;
  documents?: string;
  attribute_highlights?: {
    interest_rate?: string;
    max_amount?: string;
    [key: string]: any;
  };
}

export interface ProductAttributeValue {
  id: number;
  product_id: number;
  attribute_id: number;
  attribute?: Attribute;
  value_text?: string;
  value_number?: number;
  value_boolean?: boolean;
  value_json?: any;
}

// Product Category types
export interface ProductCategory extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  group_id?: number | null;
  group?: Group | null;
  parent_id?: number;
  parent?: ProductCategory;
  children?: ProductCategory[];
  is_active?: boolean;
}

// Group types
export interface Group extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  is_active?: boolean;
  sort_order?: number;
}

// Attribute types
export interface Attribute extends BaseEntity {
  name: string;
  slug: string;
  data_type: 'text' | 'number' | 'percentage' | 'boolean' | 'json';
  category_id?: number;
  product_category_id?: number;
  category?: ProductCategory;
  product_category?: ProductCategory; // Alias for category
  is_filterable?: boolean;
  is_active?: boolean;
  is_required?: boolean;
  unit?: string;
}

// Partner types
export interface Partner extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  logo?: string;
  logo_path?: string;
  website?: string;
  website_url?: string;
  contact_email?: string;
  contact_phone?: string;
  status?: 'active' | 'inactive';
  is_active?: boolean;
}

// Form types
export interface Form extends BaseEntity {
  name: string;
  slug: string;
  type: 'pre_form' | 'post_form';
  description?: string;
  is_active?: boolean;
  status?: 'active' | 'inactive'; // Status field
  inputs?: FormInput[];
}

export interface FormInput {
  id?: number;
  form_id?: number;
  label: string;
  name: string;
  type: 'text' | 'textarea' | 'dropdown' | 'checkbox';
  placeholder?: string;
  required?: boolean;
  options_text?: string; // For dropdown type
  order?: number;
}

// Lead types
export interface Lead extends BaseEntity {
  form_id?: number;
  form?: Form;
  product_id?: number;
  product?: Product;
  status: 'new' | 'contacted' | 'qualified' | 'converted' | 'rejected';
  data?: Record<string, any>;
  notes?: string;
  // Direct properties that might be in data
  full_name?: string;
  name?: string;
  email?: string;
  mobile_number?: string;
  phone?: string;
  source?: string;
  product_category?: ProductCategory;
  message?: string;
  meta?: Record<string, any>;
}

// Blog Post types
export interface BlogPost extends BaseEntity {
  title: string;
  slug: string;
  excerpt?: string;
  content?: string;
  featured_image?: string;
  published_at?: string;
  is_published?: boolean;
  status?: 'draft' | 'published' | 'archived';
  category?: string;
  seo_title?: string;
  seo_description?: string;
  seo_keywords?: string;
}

// CMS Page types
export interface CmsPage extends BaseEntity {
  title: string;
  slug: string;
  content?: string;
  is_active?: boolean;
  status?: 'draft' | 'published';
  seo_title?: string;
  seo_description?: string;
  seo_keywords?: string;
}

// FAQ types
export interface Faq extends BaseEntity {
  question: string;
  answer: string;
  order?: number;
  is_active?: boolean;
}

// Activity Log types
export interface ActivityLog extends BaseEntity {
  log_name?: string;
  description: string;
  subject_type?: string;
  subject_id?: number;
  causer_type?: string;
  causer_id?: number;
  causer?: {
    id: number;
    name: string;
    email?: string;
  };
  properties?: Record<string, any>;
}

// Settings types
export interface SiteSetting {
  key: string;
  value: string | number | boolean | null;
  type?: string;
}

// Dashboard Stats
export interface DashboardStats {
  total_products: number;
  total_partners: number;
  total_leads: number;
  total_users: number;
}

// Store types
export interface StoreState<T = any> {
  items: T[];
  currentItem: T | null;
  loading: boolean;
  error: any;
  pagination: PaginationMeta;
}

export interface StoreActions<T = any> {
  fetchItems: (params?: Record<string, any>) => Promise<T[]>;
  fetchItem: (id: number | string) => Promise<T>;
  createItem: (data: Partial<T>) => Promise<T>;
  updateItem: (id: number | string, data: Partial<T>) => Promise<T>;
  deleteItem: (id: number | string) => Promise<void>;
  clearCurrentItem: () => void;
  clearError: () => void;
}

