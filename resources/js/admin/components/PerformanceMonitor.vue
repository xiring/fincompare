<template>
  <div v-if="isEnabled" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg border border-charcoal-200 p-3 max-w-sm z-50" style="max-height: 80vh; overflow-y: auto;">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-sm font-semibold text-charcoal-800">Performance Monitor</h3>
      <button
        @click="toggleExpanded"
        class="text-charcoal-500 hover:text-charcoal-700"
        :title="expanded ? 'Collapse' : 'Expand'"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            :d="expanded ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"
          />
        </svg>
      </button>
    </div>

    <div v-if="expanded" class="space-y-3 text-xs">
      <!-- Warnings Banner -->
      <div v-if="report.warnings && (report.warnings.memoryLeak || report.warnings.slowRequests > 0)" class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-2">
        <div class="flex items-center gap-2 text-yellow-800">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <span class="font-medium text-xs">Performance Warnings</span>
        </div>
        <div class="mt-1 space-y-1 text-yellow-700 text-xs">
          <div v-if="report.warnings.slowRequests > 0">
            {{ report.warnings.slowRequests }} slow API request(s) detected
          </div>
          <div v-if="report.warnings.memoryLeak">
            Potential memory leak: +{{ report.warnings.memoryLeak.growth }}MB growth
          </div>
        </div>
      </div>

      <!-- Page Load Stats -->
      <div v-if="report.pageLoads">
        <h4 class="font-medium text-charcoal-700 mb-1">Page Load</h4>
        <div class="space-y-1 text-charcoal-600">
          <div class="flex justify-between">
            <span>TTI (avg):</span>
            <span class="font-mono">{{ report.pageLoads.avgTTI ? `${report.pageLoads.avgTTI}ms` : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span>FCP (avg):</span>
            <span class="font-mono">{{ report.pageLoads.avgFCP ? `${report.pageLoads.avgFCP}ms` : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span>Load Time (avg):</span>
            <span class="font-mono">{{ report.pageLoads.avgLoadTime }}ms</span>
          </div>
        </div>
      </div>

      <!-- API Request Stats -->
      <div v-if="report.apiRequests">
        <h4 class="font-medium text-charcoal-700 mb-1">API Requests</h4>
        <div class="space-y-1 text-charcoal-600">
          <div class="flex justify-between">
            <span>Total:</span>
            <span class="font-mono">{{ report.apiRequests.total }}</span>
          </div>
          <div class="flex justify-between">
            <span>Avg Duration:</span>
            <span class="font-mono">{{ report.apiRequests.avgDuration }}ms</span>
          </div>
          <div v-if="report.apiRequests.slowRequests > 0" class="flex justify-between text-yellow-600 font-medium">
            <span>⚠️ Slow (&gt;1s):</span>
            <span class="font-mono">{{ report.apiRequests.slowRequests }}</span>
          </div>
        </div>
      </div>

      <!-- Memory Stats -->
      <div v-if="report.memory">
        <h4 class="font-medium text-charcoal-700 mb-1">Memory</h4>
        <div class="space-y-1 text-charcoal-600">
          <div class="flex justify-between">
            <span>Used:</span>
            <span class="font-mono">{{ report.memory.current.used }}MB</span>
          </div>
          <div class="flex justify-between">
            <span>Limit:</span>
            <span class="font-mono">{{ report.memory.current.limit }}MB</span>
          </div>
          <div v-if="report.warnings?.memoryLeak" class="flex justify-between text-red-600 font-medium mt-2 pt-2 border-t border-charcoal-200">
            <span>⚠️ Memory Leak:</span>
            <span class="font-mono">+{{ report.warnings.memoryLeak.growth }}MB</span>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="pt-2 border-t border-charcoal-200 flex gap-2">
        <button
          @click="exportMetrics"
          class="px-2 py-1 text-xs bg-primary-500 text-white rounded hover:bg-primary-600"
        >
          Export
        </button>
        <button
          @click="clearMetrics"
          class="px-2 py-1 text-xs bg-charcoal-100 text-charcoal-700 rounded hover:bg-charcoal-200"
        >
          Clear
        </button>
        <button
          @click="toggleMonitor"
          class="px-2 py-1 text-xs bg-charcoal-100 text-charcoal-700 rounded hover:bg-charcoal-200"
        >
          {{ isEnabled ? 'Disable' : 'Enable' }}
        </button>
      </div>
    </div>

    <!-- Collapsed view -->
    <div v-else class="text-xs text-charcoal-600">
      <div v-if="report.apiRequests" class="flex justify-between">
        <span>API Requests:</span>
        <span class="font-mono">{{ report.apiRequests.total }}</span>
      </div>
      <div v-if="report.memory" class="flex justify-between mt-1">
        <span>Memory:</span>
        <span class="font-mono">{{ report.memory.current.used }}MB</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { performanceMonitor } from '../utils/performanceMonitor';

const isEnabled = ref(performanceMonitor.isEnabled);
const expanded = ref(false); // Start collapsed by default
const report = ref({});

let updateInterval = null;

const updateReport = () => {
  report.value = performanceMonitor.getReport();
};

const toggleExpanded = () => {
  expanded.value = !expanded.value;
};

const exportMetrics = () => {
  const data = performanceMonitor.exportMetrics();
  const blob = new Blob([data], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `performance-metrics-${Date.now()}.json`;
  a.click();
  URL.revokeObjectURL(url);
};

const clearMetrics = () => {
  if (confirm('Clear all performance metrics?')) {
    performanceMonitor.clear();
    updateReport();
  }
};

const toggleMonitor = () => {
  isEnabled.value = !isEnabled.value;
  performanceMonitor.setEnabled(isEnabled.value);
  if (isEnabled.value) {
    updateReport();
  }
};

onMounted(() => {
  updateReport();
  // Update every 5 seconds
  updateInterval = setInterval(updateReport, 5000);
});

onUnmounted(() => {
  if (updateInterval) {
    clearInterval(updateInterval);
  }
});
</script>

