/**
 * Product Categories Store
 * Manages product categories state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useProductCategoriesStore = createBaseStore('productCategories', adminApi.productCategories);
