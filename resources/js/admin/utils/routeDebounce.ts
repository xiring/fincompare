/**
 * Route Update Debouncer
 * Prevents multiple rapid router.replace() calls
 */

import type { Router } from 'vue-router';

let routeUpdateTimeout: ReturnType<typeof setTimeout> | null = null;
let pendingRouteUpdate: Record<string, any> | null = null;

export function debounceRouteUpdate(
  router: Router,
  query: Record<string, any>,
  delay: number = 150
): void {
  // Store the latest query
  pendingRouteUpdate = query;

  // Clear existing timeout
  if (routeUpdateTimeout) {
    clearTimeout(routeUpdateTimeout);
  }

  // Set new timeout
  routeUpdateTimeout = setTimeout(() => {
    if (pendingRouteUpdate) {
      router.replace({ query: pendingRouteUpdate });
      pendingRouteUpdate = null;
    }
    routeUpdateTimeout = null;
  }, delay);
}

