/**
 * Users Store
 * Manages users state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useUsersStore = createBaseStore('users', adminApi.users);
