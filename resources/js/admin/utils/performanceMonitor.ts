/**
 * Performance Monitoring Utility
 * Tracks and reports key performance metrics for the admin panel
 */

interface PageLoadMetrics {
  timestamp: number;
  url: string;
  tti: number | null;
  fcp: number | null;
  loadTime: number;
  domContentLoaded: number;
  dnsTime: number;
  connectionTime: number;
  responseTime: number;
}

interface ApiRequestMetrics {
  timestamp: number;
  url: string;
  method: string;
  duration: number;
  status: number;
}

interface MemoryMetrics {
  timestamp: number;
  used: number;
  total: number;
  limit: number;
}

interface PerformanceMemory {
  usedJSHeapSize: number;
  totalJSHeapSize: number;
  jsHeapSizeLimit: number;
}

declare global {
  interface Performance {
    memory?: PerformanceMemory;
  }
}

class PerformanceMonitor {
  private metrics: {
    apiRequests: ApiRequestMetrics[];
    pageLoads: PageLoadMetrics[];
    memoryUsage: MemoryMetrics[];
    bundleSize: number | null;
  };
  private observers: {
    performance: PerformanceObserver | null;
    memory: number | null;
  };
  public isEnabled: boolean;

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
    this.isEnabled =
      (typeof process !== 'undefined' && process.env?.NODE_ENV === 'development') ||
      this.getSetting('performanceMonitoring', false);
  }

  /**
   * Get a setting from localStorage
   */
  private getSetting(key: string, defaultValue: any): any {
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
  private setSetting(key: string, value: any): void {
    try {
      localStorage.setItem(`perf_${key}`, JSON.stringify(value));
    } catch (error) {
      console.warn('Failed to save performance setting:', error);
    }
  }

  /**
   * Initialize performance monitoring
   */
  init(): void {
    if (!this.isEnabled) return;

    // Track page load performance
    this.trackPageLoad();

    // Monitor memory usage (if available)
    if (typeof performance !== 'undefined' && performance.memory) {
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
  private trackPageLoad(): void {
    if (typeof window === 'undefined' || !window.performance) return;

    window.addEventListener('load', () => {
      setTimeout(() => {
        const perfData = window.performance.timing;
        const navigationEntries = performance.getEntriesByType('navigation');
    const navigation = (navigationEntries && navigationEntries[0]) as PerformanceNavigationTiming | undefined;

        const metrics: PageLoadMetrics = {
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
        if (typeof process !== 'undefined' && process.env?.NODE_ENV === 'development') {
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
  private getFCP(): number | null {
    try {
      const paintEntries = performance.getEntriesByType('paint') as PerformancePaintTiming[];
      const fcpEntry = paintEntries.find((entry) => entry.name === 'first-contentful-paint');
      return fcpEntry ? Math.round(fcpEntry.startTime) : null;
    } catch {
      return null;
    }
  }

  /**
   * Track API requests
   */
  private trackApiRequests(): void {
    // This should be integrated with the API client
    // For now, we'll provide methods to track manually
  }

  /**
   * Record an API request
   */
  recordApiRequest(url: string, method: string, duration: number, status: number): void {
    if (!this.isEnabled) return;

    const request: ApiRequestMetrics = {
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
    if (typeof process !== 'undefined' && process.env?.NODE_ENV === 'development' && duration > 1000) {
      console.warn(`⚠️ Slow API request: ${method} ${url} took ${duration}ms`);
    }
  }

  /**
   * Start monitoring memory usage
   */
  private startMemoryMonitoring(): void {
    if (!performance.memory) return;

    const monitor = () => {
      const memory: MemoryMetrics = {
        timestamp: Date.now(),
        used: performance.memory!.usedJSHeapSize,
        total: performance.memory!.totalJSHeapSize,
        limit: performance.memory!.jsHeapSizeLimit,
      };

      this.metrics.memoryUsage.push(memory);

      // Keep only last 100 measurements
      if (this.metrics.memoryUsage.length > 100) {
        this.metrics.memoryUsage.shift();
      }

      // Check for memory leaks (growing memory usage)
      if (this.metrics.memoryUsage.length > 10) {
        const recent = this.metrics.memoryUsage.slice(-10);
        const last = recent[recent.length - 1];
        const first = recent[0];
        if (!last || !first) return;
        const trend = last.used - first.used;
        if (trend > 10 * 1024 * 1024) {
          // 10MB growth
          console.warn('⚠️ Potential memory leak detected:', {
            growth: `${(trend / 1024 / 1024).toFixed(2)}MB`,
            current: `${(memory.used / 1024 / 1024).toFixed(2)}MB`,
          });
        }
      }
    };

    // Monitor every 5 seconds
    this.observers.memory = window.setInterval(monitor, 5000);
    monitor(); // Initial measurement
  }

  /**
   * Stop memory monitoring
   */
  private stopMemoryMonitoring(): void {
    if (this.observers.memory) {
      clearInterval(this.observers.memory);
      this.observers.memory = null;
    }
  }

  /**
   * Estimate bundle size
   */
  private estimateBundleSize(): void {
    if (typeof window === 'undefined') return;

    try {
      // Get all script tags
      const scripts = Array.from(document.querySelectorAll('script[src]'));
      let totalSize = 0;

      // This is an approximation - actual bundle size should be measured during build
      scripts.forEach((script) => {
        const src = (script as HTMLScriptElement).src;
        if (src.includes('/js/admin') || src.includes('app.ts') || src.includes('app.js')) {
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
  getReport(): any {
    const memoryStats = this.getMemoryStats();
    const apiStats = this.getApiRequestStats();

    // Check for memory leak in current report
    let memoryLeakWarning: { growth: number; current: number } | null = null;
    if (memoryStats && this.metrics.memoryUsage.length > 10) {
      const recent = this.metrics.memoryUsage.slice(-10);
      const last = recent[recent.length - 1];
      const first = recent[0];
      if (last && first) {
        const trend = last.used - first.used;
        if (trend > 10 * 1024 * 1024) {
          // 10MB growth
          memoryLeakWarning = {
            growth: Math.round(trend / 1024 / 1024), // MB
            current: memoryStats.current.used,
          };
        }
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
        slowRequests: apiStats?.slowRequests && apiStats.slowRequests > 0 ? apiStats.slowRequests : 0,
      },
    };

    return report;
  }

  /**
   * Get page load statistics
   */
  private getPageLoadStats(): any {
    if (this.metrics.pageLoads.length === 0) return null;

    const loads = this.metrics.pageLoads;
    const ttiValues = loads.map((l) => l.tti).filter((v): v is number => v !== null);
    const fcpValues = loads.map((l) => l.fcp).filter((v): v is number => v !== null);
    const loadTimes = loads.map((l) => l.loadTime);

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
  private getApiRequestStats(): any {
    if (this.metrics.apiRequests.length === 0) return null;

    const requests = this.metrics.apiRequests;
    if (!requests || requests.length === 0) return null;
    const durations = requests.map((r) => r.duration);
    const byMethod: Record<string, number> = requests.reduce((acc, req) => {
      const method = req?.method || 'UNKNOWN';
      acc[method] = (acc[method] || 0) + 1;
      return acc;
    }, {} as Record<string, number>);

    return {
      total: requests.length,
      avgDuration: Math.round(durations.reduce((a, b) => a + b, 0) / durations.length),
      minDuration: Math.min(...durations),
      maxDuration: Math.max(...durations),
      byMethod,
      slowRequests: requests.filter((r) => r.duration > 1000).length,
    };
  }

  /**
   * Get memory statistics
   */
  private getMemoryStats(): any {
    if (this.metrics.memoryUsage.length === 0) return null;

    const usage = this.metrics.memoryUsage;
    if (usage.length === 0) return null;
    const current = usage[usage.length - 1];
    if (!current) return null;
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
  exportMetrics(): string {
    return JSON.stringify(this.getReport(), null, 2);
  }

  /**
   * Clear all metrics
   */
  clear(): void {
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
  setEnabled(enabled: boolean): void {
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

