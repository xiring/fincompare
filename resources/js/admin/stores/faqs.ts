/**
 * FAQs Store
 * Manages FAQs state and operations
 */

import { adminApi } from '../services/api/index';
import { createBaseStore } from './utils/baseStore';
import type { Faq } from '../types/index';

export const useFaqsStore = createBaseStore<Faq>('faqs', adminApi.faqs, {
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item as Faq);
  },
});

