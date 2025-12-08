/**
 * Performance Monitoring Utility
 * Tracks and reports key performance metrics for the admin panel
 */

class PerformanceMonitor {
  constructor() {
    this.metrics = {
      apiRequests: [],
      pageLoads: [],
      memoryUsage: [],
      bundleSize: null,
    };
    this.observers = {
      performance: null,
      memory: null,
    };
    this.isEnabled = process.env.NODE_ENV === 'development' || this.getSetting('performanceMonitoring', false);
  }

  /**
   * Get a setting from localStorage
   */
  getSetting(key, defaultValue) {
    try {
      const value = localStorage.getItem(`perf_${key}`);
      return value !== null ? JSON.parse(value) : defaultValue;
    } catch {
      return defaultValue;
    }
  }

  /**
   * Set a setting in localStorage
   */
  setSetting(key, value) {
    try {
      localStorage.setItem(`perf_${key}`, JSON.stringify(value));
    } catch (error) {
      console.warn('Failed to save performance setting:', error);
    }
  }

  /**
   * Initialize performance monitoring
   */
  init() {
    if (!this.isEnabled) return;

    // Track page load performance
    this.trackPageLoad();

    // Monitor memory usage (if available)
    if (performance.memory) {
      this.startMemoryMonitoring();
    }

    // Track API requests
    this.trackApiRequests();

    // Get bundle size
    this.estimateBundleSize();
  }

  /**
   * Track page load performance
   */
  trackPageLoad() {
    if (typeof window === 'undefined' || !window.performance) return;

    window.addEventListener('load', () => {
      setTimeout(() => {
        const perfData = window.performance.timing;
        const navigation = performance.getEntriesByType('navigation')[0];

        const metrics = {
          timestamp: Date.now(),
          url: window.location.href,
          // Time to Interactive (TTI) - approximate
          tti: navigation ? navigation.domInteractive - navigation.fetchStart : null,
          // First Contentful Paint (FCP)
          fcp: this.getFCP(),
          // Total page load time
          loadTime: perfData.loadEventEnd - perfData.navigationStart,
          // DOM content loaded
          domContentLoaded: perfData.domContentLoadedEventEnd - perfData.navigationStart,
          // DNS lookup time
          dnsTime: perfData.domainLookupEnd - perfData.domainLookupStart,
          // Connection time
          connectionTime: perfData.connectEnd - perfData.connectStart,
          // Response time
          responseTime: perfData.responseEnd - perfData.requestStart,
        };

        this.metrics.pageLoads.push(metrics);

        // Keep only last 50 page loads
        if (this.metrics.pageLoads.length > 50) {
          this.metrics.pageLoads.shift();
        }

        // Log in development
        if (process.env.NODE_ENV === 'development') {
          console.group('📊 Page Load Performance');
          console.log('TTI (approx):', metrics.tti ? `${metrics.tti}ms` : 'N/A');
          console.log('FCP:', metrics.fcp ? `${metrics.fcp}ms` : 'N/A');
          console.log('Load Time:', `${metrics.loadTime}ms`);
          console.log('DOM Content Loaded:', `${metrics.domContentLoaded}ms`);
          console.groupEnd();
        }
      }, 0);
    });
  }

  /**
   * Get First Contentful Paint (FCP)
   */
  getFCP() {
    try {
      const paintEntries = performance.getEntriesByType('paint');
      const fcpEntry = paintEntries.find(entry => entry.name === 'first-contentful-paint');
      return fcpEntry ? Math.round(fcpEntry.startTime) : null;
    } catch {
      return null;
    }
  }

  /**
   * Track API requests
   */
  trackApiRequests() {
    // This should be integrated with the API client
    // For now, we'll provide methods to track manually
  }

  /**
   * Record an API request
   */
  recordApiRequest(url, method, duration, status) {
    if (!this.isEnabled) return;

    const request = {
      timestamp: Date.now(),
      url,
      method,
      duration,
      status,
    };

    this.metrics.apiRequests.push(request);

    // Keep only last 100 requests
    if (this.metrics.apiRequests.length > 100) {
      this.metrics.apiRequests.shift();
    }

    // Log slow requests in development
    if (process.env.NODE_ENV === 'development' && duration > 1000) {
      console.warn(`⚠️ Slow API request: ${method} ${url} took ${duration}ms`);
    }
  }

  /**
   * Start monitoring memory usage
   */
  startMemoryMonitoring() {
    if (!performance.memory) return;

    const monitor = () => {
      const memory = {
        timestamp: Date.now(),
        used: performance.memory.usedJSHeapSize,
        total: performance.memory.totalJSHeapSize,
        limit: performance.memory.jsHeapSizeLimit,
      };

      this.metrics.memoryUsage.push(memory);

      // Keep only last 100 measurements
      if (this.metrics.memoryUsage.length > 100) {
        this.metrics.memoryUsage.shift();
      }

      // Check for memory leaks (growing memory usage)
      if (this.metrics.memoryUsage.length > 10) {
        const recent = this.metrics.memoryUsage.slice(-10);
        const trend = recent[recent.length - 1].used - recent[0].used;
        if (trend > 10 * 1024 * 1024) { // 10MB growth
          console.warn('⚠️ Potential memory leak detected:', {
            growth: `${(trend / 1024 / 1024).toFixed(2)}MB`,
            current: `${(memory.used / 1024 / 1024).toFixed(2)}MB`,
          });
        }
      }
    };

    // Monitor every 5 seconds
    this.observers.memory = setInterval(monitor, 5000);
    monitor(); // Initial measurement
  }

  /**
   * Stop memory monitoring
   */
  stopMemoryMonitoring() {
    if (this.observers.memory) {
      clearInterval(this.observers.memory);
      this.observers.memory = null;
    }
  }

  /**
   * Estimate bundle size
   */
  estimateBundleSize() {
    if (typeof window === 'undefined') return;

    try {
      // Get all script tags
      const scripts = Array.from(document.querySelectorAll('script[src]'));
      let totalSize = 0;

      // This is an approximation - actual bundle size should be measured during build
      scripts.forEach(script => {
        const src = script.src;
        if (src.includes('/js/admin') || src.includes('app.js')) {
          // We can't get actual size without fetching, so we'll estimate
          // In production, this should be set during build time
          totalSize += 0; // Placeholder
        }
      });

      this.metrics.bundleSize = totalSize;
    } catch (error) {
      console.warn('Failed to estimate bundle size:', error);
    }
  }

  /**
   * Get performance report
   */
  getReport() {
    const memoryStats = this.getMemoryStats();
    const apiStats = this.getApiRequestStats();

    // Check for memory leak in current report
    let memoryLeakWarning = null;
    if (memoryStats && this.metrics.memoryUsage.length > 10) {
      const recent = this.metrics.memoryUsage.slice(-10);
      const trend = recent[recent.length - 1].used - recent[0].used;
      if (trend > 10 * 1024 * 1024) { // 10MB growth
        memoryLeakWarning = {
          growth: Math.round(trend / 1024 / 1024), // MB
          current: memoryStats.current.used,
        };
      }
    }

    const report = {
      timestamp: Date.now(),
      pageLoads: this.getPageLoadStats(),
      apiRequests: apiStats,
      memory: memoryStats,
      bundleSize: this.metrics.bundleSize,
      warnings: {
        memoryLeak: memoryLeakWarning,
        slowRequests: apiStats?.slowRequests > 0 ? apiStats.slowRequests : 0,
      },
    };

    return report;
  }

  /**
   * Get page load statistics
   */
  getPageLoadStats() {
    if (this.metrics.pageLoads.length === 0) return null;

    const loads = this.metrics.pageLoads;
    const ttiValues = loads.map(l => l.tti).filter(v => v !== null);
    const fcpValues = loads.map(l => l.fcp).filter(v => v !== null);
    const loadTimes = loads.map(l => l.loadTime);

    return {
      count: loads.length,
      avgTTI: ttiValues.length > 0 ? Math.round(ttiValues.reduce((a, b) => a + b, 0) / ttiValues.length) : null,
      avgFCP: fcpValues.length > 0 ? Math.round(fcpValues.reduce((a, b) => a + b, 0) / fcpValues.length) : null,
      avgLoadTime: Math.round(loadTimes.reduce((a, b) => a + b, 0) / loadTimes.length),
      minLoadTime: Math.min(...loadTimes),
      maxLoadTime: Math.max(...loadTimes),
    };
  }

  /**
   * Get API request statistics
   */
  getApiRequestStats() {
    if (this.metrics.apiRequests.length === 0) return null;

    const requests = this.metrics.apiRequests;
    const durations = requests.map(r => r.duration);
    const byMethod = requests.reduce((acc, req) => {
      acc[req.method] = (acc[req.method] || 0) + 1;
      return acc;
    }, {});

    return {
      total: requests.length,
      avgDuration: Math.round(durations.reduce((a, b) => a + b, 0) / durations.length),
      minDuration: Math.min(...durations),
      maxDuration: Math.max(...durations),
      byMethod,
      slowRequests: requests.filter(r => r.duration > 1000).length,
    };
  }

  /**
   * Get memory statistics
   */
  getMemoryStats() {
    if (this.metrics.memoryUsage.length === 0) return null;

    const usage = this.metrics.memoryUsage;
    const current = usage[usage.length - 1];
    const avgUsed = usage.reduce((sum, m) => sum + m.used, 0) / usage.length;

    return {
      current: {
        used: Math.round(current.used / 1024 / 1024), // MB
        total: Math.round(current.total / 1024 / 1024), // MB
        limit: Math.round(current.limit / 1024 / 1024), // MB
      },
      avgUsed: Math.round(avgUsed / 1024 / 1024), // MB
      samples: usage.length,
    };
  }

  /**
   * Export metrics as JSON
   */
  exportMetrics() {
    return JSON.stringify(this.getReport(), null, 2);
  }

  /**
   * Clear all metrics
   */
  clear() {
    this.metrics = {
      apiRequests: [],
      pageLoads: [],
      memoryUsage: [],
      bundleSize: null,
    };
  }

  /**
   * Enable/disable monitoring
   */
  setEnabled(enabled) {
    this.isEnabled = enabled;
    this.setSetting('performanceMonitoring', enabled);

    if (enabled) {
      this.init();
    } else {
      this.stopMemoryMonitoring();
    }
  }
}

// Create singleton instance
export const performanceMonitor = new PerformanceMonitor();

// Auto-initialize in development
if (typeof window !== 'undefined') {
  performanceMonitor.init();
}

