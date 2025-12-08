/**
 * Activity Store
 * Manages activity log state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useActivityStore = createBaseStore('activity', adminApi.activity, {
  // Activity is read-only, no CRUD operations
  extraState: {},
  extraActions: {},
});
