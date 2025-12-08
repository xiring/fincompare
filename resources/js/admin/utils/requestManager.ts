/**
 * Request Manager
 * Handles request cancellation and deduplication
 */

import type { CancelTokenSource } from 'axios';

class RequestManager {
  private pendingRequests: Map<string, Promise<any>>;
  private cancelTokens: Map<string, CancelTokenSource>;

  constructor() {
    this.pendingRequests = new Map();
    this.cancelTokens = new Map();
  }

  /**
   * Create a unique key for a request
   * For POST/PUT/DELETE, includes request body/data in the key
   */
  getRequestKey(
    url: string,
    method: string = 'GET',
    params: Record<string, any> = {},
    data: any = null
  ): string {
    const sortedParams = Object.keys(params)
      .sort()
      .map((key) => `${key}=${JSON.stringify(params[key])}`)
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
  isPending(key: string): boolean {
    return this.pendingRequests.has(key);
  }

  /**
   * Get pending request promise
   */
  getPending<T = any>(key: string): Promise<T> | undefined {
    return this.pendingRequests.get(key) as Promise<T> | undefined;
  }

  /**
   * Add a pending request
   */
  addPending<T = any>(key: string, promise: Promise<T>, cancelToken?: CancelTokenSource | null): void {
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
  cancel(key: string): void {
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
  cancelAll(): void {
    this.cancelTokens.forEach((cancelToken) => {
      cancelToken.cancel?.('All requests cancelled');
    });
    this.pendingRequests.clear();
    this.cancelTokens.clear();
  }
}

export const requestManager = new RequestManager();

