/**
 * Shared Type Definitions
 */

// Vue component shims
declare module '*.vue' {
  import type { DefineComponent } from 'vue';
  const component: DefineComponent<{}, {}, any>;
  export default component;
}

// Specific component type definitions with slots
declare module '../public/components/HeroSection.vue' {
  import type { DefineComponent } from 'vue';
  const component: DefineComponent<{
    title: string;
    subtitle?: string;
  }, {}, any, {}, {}, any, {
    breadcrumb?: any;
    default?: any;
  }>;
  export default component;
}

declare module '../public/components/LoadingSkeleton.vue' {
  import type { DefineComponent } from 'vue';
  const component: DefineComponent<{
    count?: number;
    containerClass?: string;
    itemClass?: string;
  }, {}, any, {}, {}, any, {
    default?: (props: { index: number }) => any;
  }>;
  export default component;
}

// Global window extensions
declare global {
  interface Window {
    axios: import('axios').AxiosInstance;
  }
}

// API Response Types
export interface ApiResponse<T = any> {
  data: T;
  message?: string;
  status?: number;
}

export interface PaginatedResponse<T = any> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
  prev_page_url: string | null;
  next_page_url: string | null;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
  prev_page_url: string | null;
  next_page_url: string | null;
}

// Error types
export interface ValidationError {
  message: string;
  errors: Record<string, string[]>;
}

export interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
  status?: number;
}

// Common entity types
export interface BaseEntity {
  id: number;
  created_at?: string;
  updated_at?: string;
}

// Form types
export interface FormErrors {
  [key: string]: string | string[];
}

// Router meta
export interface RouteMeta {
  title?: string;
  requiresAuth?: boolean;
  requiresGuest?: boolean;
  [key: string]: any;
}

// Entity Types
export interface Product extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  price?: number;
  image?: string;
  image_url?: string;
  status: 'published' | 'draft' | 'active' | 'inactive';
  category_id?: number;
  partner_id?: number;
  featured?: boolean;
  is_featured?: boolean;
  attributeValues?: ProductAttributeValue[];
  partner?: Partner;
  category?: ProductCategory;
  product_category?: ProductCategory;
  eligibility?: string;
  documents?: string;
  attribute_highlights?: {
    interest_rate?: string;
    max_amount?: string;
    [key: string]: any;
  };
}

export interface ProductAttributeValue extends BaseEntity {
  attribute_id: number;
  product_id: number;
  value_text?: string;
  value_number?: number;
  value_boolean?: boolean;
  value_json?: Record<string, any>;
  attribute?: ProductAttribute;
}

export interface ProductAttribute extends BaseEntity {
  name: string;
  slug: string;
  data_type: 'text' | 'number' | 'boolean' | 'json';
  category_id?: number;
}

export interface ProductCategory extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  image?: string;
  image_url?: string;
  parent_id?: number;
  group_id?: number | null;
  group?: Group | null;
}

export interface Group extends BaseEntity {
  name: string;
  slug: string;
  description?: string;
  is_active?: boolean;
  sort_order?: number;
}

export interface Partner extends BaseEntity {
  name: string;
  slug: string;
  logo?: string;
  logo_url?: string;
  description?: string;
  website_url?: string;
}

export interface BlogPost extends BaseEntity {
  title: string;
  slug: string;
  content?: string;
  excerpt?: string;
  featured_image?: string;
  status: 'published' | 'draft';
  published_at?: string;
  category?: string;
  tags?: string[];
}

export interface Faq extends BaseEntity {
  question: string;
  answer: string;
  category?: string;
  order?: number;
}

export interface CmsPage extends BaseEntity {
  title: string;
  slug: string;
  content?: string;
  status: 'published' | 'draft';
}

export interface Form extends BaseEntity {
  name: string;
  slug: string;
  type: 'pre_form' | 'post_form';
  inputs?: FormInput[];
}

export interface FormInput extends BaseEntity {
  form_id: number;
  label: string;
  name: string;
  type: string;
  required: boolean;
  options?: string;
  order: number;
}

export interface User extends BaseEntity {
  name: string;
  email: string;
  email_verified_at?: string;
}

export interface Role extends BaseEntity {
  name: string;
  guard_name: string;
}

export interface Permission extends BaseEntity {
  name: string;
  guard_name: string;
}

export interface Lead extends BaseEntity {
  name: string;
  email: string;
  phone?: string;
  city?: string;
  message?: string;
  status: 'new' | 'contacted' | 'converted' | 'rejected';
  product_id?: number;
  form_id?: number;
}

export interface ActivityLog extends BaseEntity {
  log_name?: string;
  description: string;
  subject_type?: string;
  subject_id?: number;
  causer_type?: string;
  causer_id?: number;
  properties?: Record<string, any>;
}

export interface SiteSettings {
  site_name: string;
  site_logo?: string | null;
  site_favicon?: string | null;
  website_url?: string;
  seo_title?: string;
  seo_description?: string;
  seo_keywords?: string;
  [key: string]: any;
}

export interface Toast {
  id: string;
  message: string;
  type: 'success' | 'error' | 'warning' | 'info';
  duration?: number;
}

export interface Attribute extends BaseEntity {
  name: string;
  slug: string;
  data_type: 'text' | 'number' | 'boolean' | 'json';
  category_id?: number;
}

