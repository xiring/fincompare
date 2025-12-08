/**
 * Partners Store
 * Manages partners state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const usePartnersStore = createBaseStore('partners', adminApi.partners);
