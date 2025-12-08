/**
 * CMS Pages Store
 * Manages CMS pages state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useCmsPagesStore = createBaseStore('cmsPages', adminApi.cmsPages);
