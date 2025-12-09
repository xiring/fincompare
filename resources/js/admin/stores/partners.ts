/**
 * Partners Store
 * Manages partners state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Partner } from '../types/index';

export const usePartnersStore = createBaseStore<Partner>('partners', adminApi.partners);

