/**
 * Request Manager
 * Handles request cancellation and deduplication
 */

class RequestManager {
  constructor() {
    this.pendingRequests = new Map();
    this.cancelTokens = new Map();
  }

  /**
   * Create a unique key for a request
   * For POST/PUT/DELETE, includes request body/data in the key
   */
  getRequestKey(url, method = 'GET', params = {}, data = null) {
    const sortedParams = Object.keys(params)
      .sort()
      .map(key => `${key}=${JSON.stringify(params[key])}`)
      .join('&');

    // For mutation requests, include request body in the key
    const methodUpper = method.toUpperCase();
    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(methodUpper) && data !== null && data !== undefined) {
      // Create a stable hash of the data
      const dataHash = typeof data === 'string' ? data : JSON.stringify(data);
      return `${methodUpper}:${url}?${sortedParams}|body:${dataHash}`;
    }

    return `${methodUpper}:${url}?${sortedParams}`;
  }

  /**
   * Check if a request is already pending
   */
  isPending(key) {
    return this.pendingRequests.has(key);
  }

  /**
   * Get pending request promise
   */
  getPending(key) {
    return this.pendingRequests.get(key);
  }

  /**
   * Add a pending request
   */
  addPending(key, promise, cancelToken) {
    this.pendingRequests.set(key, promise);
    if (cancelToken) {
      this.cancelTokens.set(key, cancelToken);
    }

    // Clean up when request completes
    promise.finally(() => {
      this.pendingRequests.delete(key);
      this.cancelTokens.delete(key);
    });
  }

  /**
   * Cancel a pending request
   */
  cancel(key) {
    const cancelToken = this.cancelTokens.get(key);
    if (cancelToken) {
      cancelToken.cancel?.('Request cancelled');
      this.pendingRequests.delete(key);
      this.cancelTokens.delete(key);
    }
  }

  /**
   * Cancel all pending requests
   */
  cancelAll() {
    this.cancelTokens.forEach((cancelToken) => {
      cancelToken.cancel?.('All requests cancelled');
    });
    this.pendingRequests.clear();
    this.cancelTokens.clear();
  }
}

export const requestManager = new RequestManager();

