/**
 * Blogs Store
 * Manages blog posts state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useBlogsStore = createBaseStore('blogs', adminApi.blogs);
