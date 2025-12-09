/**
 * Users Store
 * Manages users state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { User } from '../types/index';

export const useUsersStore = createBaseStore<User>('users', adminApi.users);

