/**
 * CMS Pages Store
 * Manages CMS pages state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { CmsPage } from '../types/index';

export const useCmsPagesStore = createBaseStore<CmsPage>('cmsPages', adminApi.cmsPages);

