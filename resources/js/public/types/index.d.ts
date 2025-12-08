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

// Vue component shims
declare module '../layouts/GuestLayout.vue';
declare module '../../layouts/GuestLayout.vue';
declare module '../../../layouts/GuestLayout.vue';
declare module './App.vue';
declare module '../App.vue';
declare module '../components/AddProductModal.vue';
declare module '../../components/AddProductModal.vue';
declare module '../components/ConfirmModal.vue';
declare module '../../components/ConfirmModal.vue';
declare module '../components/ProductCard.vue';
declare module '../../components/ProductCard.vue';
declare module '../components/HeroSection.vue' {
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
declare module '../components/LoadingSkeleton.vue' {
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

