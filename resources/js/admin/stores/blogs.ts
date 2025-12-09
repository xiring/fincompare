/**
 * Blogs Store
 * Manages blog posts state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { BlogPost } from '../types/index';

export const useBlogsStore = createBaseStore<BlogPost>('blogs', adminApi.blogs);

