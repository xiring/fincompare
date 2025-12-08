/**
 * Pagination Utility
 * Extracts pagination data from Laravel API responses
 *
 * Optimized to mutate existing pagination ref when provided to avoid object creation overhead
 */

/**
 * Extract pagination data from API response
 * @param {Object} data - API response data
 * @param {Object} existingPagination - Optional existing pagination ref to mutate (for performance)
 * @returns {Object} Pagination object
 */
export function extractPagination(data, existingPagination = null) {
  // If existing pagination ref is provided, mutate it instead of creating new object
  if (existingPagination) {
    if (!data) {
      existingPagination.current_page = 1;
      existingPagination.last_page = 1;
      existingPagination.per_page = 10;
      existingPagination.total = 0;
      existingPagination.from = 0;
      existingPagination.to = 0;
      existingPagination.prev_page_url = null;
      existingPagination.next_page_url = null;
      return existingPagination;
    }

    // Handle direct pagination format
    if (data.current_page !== undefined) {
      existingPagination.current_page = data.current_page || 1;
      existingPagination.last_page = data.last_page || 1;
      existingPagination.per_page = data.per_page || 10;
      existingPagination.total = data.total || 0;
      existingPagination.from = data.from || 0;
      existingPagination.to = data.to || 0;
      existingPagination.prev_page_url = data.prev_page_url || null;
      existingPagination.next_page_url = data.next_page_url || null;
      return existingPagination;
    }

    // Handle meta-based pagination
    if (data.meta) {
      existingPagination.current_page = data.meta.current_page || 1;
      existingPagination.last_page = data.meta.last_page || 1;
      existingPagination.per_page = data.meta.per_page || 10;
      existingPagination.total = data.meta.total || 0;
      existingPagination.from = data.meta.from || 0;
      existingPagination.to = data.meta.to || 0;
      existingPagination.prev_page_url = data.meta.prev_page_url || null;
      existingPagination.next_page_url = data.meta.next_page_url || null;
      return existingPagination;
    }

    // Default pagination
    existingPagination.current_page = 1;
    existingPagination.last_page = 1;
    existingPagination.per_page = 10;
    existingPagination.total = 0;
    existingPagination.from = 0;
    existingPagination.to = 0;
    existingPagination.prev_page_url = null;
    existingPagination.next_page_url = null;
    return existingPagination;
  }

  // Fallback: Create new object if no existing pagination provided
  if (!data) {
    return {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
      prev_page_url: null,
      next_page_url: null,
    };
  }

  // Handle direct pagination format
  if (data.current_page !== undefined) {
    return {
      current_page: data.current_page || 1,
      last_page: data.last_page || 1,
      per_page: data.per_page || 10,
      total: data.total || 0,
      from: data.from || 0,
      to: data.to || 0,
      prev_page_url: data.prev_page_url || null,
      next_page_url: data.next_page_url || null,
    };
  }

  // Handle meta-based pagination
  if (data.meta) {
    return {
      current_page: data.meta.current_page || 1,
      last_page: data.meta.last_page || 1,
      per_page: data.meta.per_page || 10,
      total: data.meta.total || 0,
      from: data.meta.from || 0,
      to: data.meta.to || 0,
      prev_page_url: data.meta.prev_page_url || null,
      next_page_url: data.meta.next_page_url || null,
    };
  }

  // Default pagination
  return {
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    from: 0,
    to: 0,
    prev_page_url: null,
    next_page_url: null,
  };
}

