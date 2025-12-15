/**
 * Groups Store
 * Manages group state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Group } from '../types/index';

export const useGroupsStore = createBaseStore<Group>('groups', adminApi.groups);


