/**
 * FAQs Store
 * Manages FAQs state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useFaqsStore = createBaseStore('faqs', adminApi.faqs, {
  onCreate: ({ state, item }) => {
    // Add to items array after creation
    state.items.value.push(item);
  },
});
