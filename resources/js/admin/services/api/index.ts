/**
 * Admin API Service
 * Main export file that aggregates all API modules
 */

import products from './modules/products';
import partners from './modules/partners';
import productCategories from './modules/productCategories';
import attributes from './modules/attributes';
import users from './modules/users';
import roles from './modules/roles';
import permissions from './modules/permissions';
import leads from './modules/leads';
import forms from './modules/forms';
import blogs from './modules/blogs';
import cmsPages from './modules/cmsPages';
import faqs from './modules/faqs';
import settings from './modules/settings';
import activity from './modules/activity';
import profile from './modules/profile';
import uploads from './modules/uploads';
import stats from './modules/stats';

// Export the aggregated API object
export const adminApi = {
  products,
  partners,
  productCategories,
  attributes,
  users,
  roles,
  permissions,
  leads,
  forms,
  blogs,
  cmsPages,
  faqs,
  settings,
  activity,
  profile,
  uploads,
  stats,
};

// Export default for backward compatibility
export default adminApi;

// Export the base client for advanced usage
export { default as apiClient } from './client';
export { getCsrfToken } from './client';

