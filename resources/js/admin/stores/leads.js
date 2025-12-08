/**
 * Leads Store
 * Manages leads state and operations
 */

import { adminApi } from '../services/api';
import { createBaseStore } from './utils/baseStore';

export const useLeadsStore = createBaseStore('leads', adminApi.leads, {
  extraActions: {
    exportLeads: async (storeState) => {
      storeState.loading.value = true;
      storeState.error.value = null;
      try {
        const response = await adminApi.leads.export();
        // Create blob and download
        const blob = new Blob([response.data], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `leads-${new Date().toISOString()}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
      } catch (err) {
        storeState.error.value = err;
        throw err;
      } finally {
        storeState.loading.value = false;
      }
    },
  },
});
