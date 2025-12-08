/**
 * Activity Store
 * Manages activity log state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { ActivityLog } from '../types/index';

export const useActivityStore = createBaseStore<ActivityLog>('activity', adminApi.activity, {
  // Activity is read-only, no CRUD operations
  extraState: {},
  extraActions: {},
});

