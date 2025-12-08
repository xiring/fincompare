/**
 * Product Categories Store
 * Manages product categories state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { ProductCategory } from '../types/index';

export const useProductCategoriesStore = createBaseStore<ProductCategory>('productCategories', adminApi.productCategories);

