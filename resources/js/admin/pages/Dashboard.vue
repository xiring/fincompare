<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-charcoal-800">Admin Dashboard</h1>
      <p class="mt-2 text-sm text-charcoal-600">Welcome back, {{ userName }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
      <router-link
        to="/admin/products"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md transition-shadow"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">Manage</p>
            <p class="mt-2 text-2xl font-bold text-charcoal-800">Products</p>
            <p class="mt-1 text-sm text-charcoal-600">{{ stats.products || 0 }} total</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 text-primary-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </router-link>

      <router-link
        to="/admin/leads"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md transition-shadow"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">View</p>
            <p class="mt-2 text-2xl font-bold text-charcoal-800">Leads</p>
            <p class="mt-1 text-sm text-charcoal-600">{{ stats.leads || 0 }} total</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 text-primary-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>
      </router-link>

      <router-link
        to="/admin/partners"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md transition-shadow"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">Manage</p>
            <p class="mt-2 text-2xl font-bold text-charcoal-800">Partners</p>
            <p class="mt-1 text-sm text-charcoal-600">{{ stats.partners || 0 }} total</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 text-primary-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
        </div>
      </router-link>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-charcoal-200 p-6">
      <h2 class="text-lg font-semibold text-charcoal-800">Recent Activity</h2>
      <div class="text-sm text-charcoal-500 mt-2">
        Activity log will appear here...
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const userName = ref('Admin');
const stats = ref({
  products: 0,
  leads: 0,
  partners: 0
});

onMounted(() => {
  // TODO: Fetch dashboard stats from API
  // For now, using placeholder values
});
</script>

