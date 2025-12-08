/**
 * Route Update Debouncer
 * Prevents multiple rapid router.replace() calls
 */

let routeUpdateTimeout = null;
let pendingRouteUpdate = null;

export function debounceRouteUpdate(router, query, delay = 150) {
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

