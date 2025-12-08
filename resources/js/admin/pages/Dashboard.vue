<template>
  <div>
    <PageHeader title="Dashboard" :description="`Welcome back, ${userName}!`" />

    <!-- Stats Cards -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <router-link
        to="/admin/products"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md hover:border-primary-300 transition-all"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">Products</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-800">{{ stats.products || 0 }}</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 group-hover:bg-primary-100">
            <ProductsIcon class="h-6 w-6 text-primary-600" />
          </div>
        </div>
      </router-link>

      <router-link
        to="/admin/leads"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md hover:border-primary-300 transition-all"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">Leads</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-800">{{ stats.leads || 0 }}</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-50 group-hover:bg-green-100">
            <LeadsIcon class="h-6 w-6 text-green-600" />
          </div>
        </div>
      </router-link>

      <router-link
        to="/admin/partners"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md hover:border-primary-300 transition-all"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">Partners</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-800">{{ stats.partners || 0 }}</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-50 group-hover:bg-blue-100">
            <PartnersIcon class="h-6 w-6 text-blue-600" />
          </div>
        </div>
      </router-link>

      <router-link
        to="/admin/users"
        class="group relative bg-white rounded-lg shadow-sm border border-charcoal-200 p-6 hover:shadow-md hover:border-primary-300 transition-all"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-charcoal-500">Users</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-800">{{ stats.users || 0 }}</p>
          </div>
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-50 group-hover:bg-purple-100">
            <UsersIcon class="h-6 w-6 text-purple-600" />
          </div>
        </div>
      </router-link>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 mb-8">
      <!-- Recent Leads -->
      <FormCard>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-charcoal-800">Recent Leads</h2>
          <router-link
            to="/admin/leads"
            class="text-sm text-primary-600 hover:text-primary-700 font-medium"
          >
            View All
          </router-link>
        </div>
        <LoadingSpinner v-if="loadingLeads" text="Loading leads..." />
        <div v-else-if="recentLeads.length === 0" class="text-sm text-charcoal-500 py-8 text-center">
          No leads yet
        </div>
        <div v-else class="space-y-3">
          <router-link
            v-for="lead in recentLeads"
            :key="lead.id"
            :to="`/admin/leads/${lead.id}`"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-charcoal-50 transition-colors group"
          >
            <div class="flex-shrink-0 w-10 h-10 bg-primary-50 rounded-full flex items-center justify-center">
              <span class="text-sm font-medium text-primary-600">{{ getInitials(lead.name || lead.email) }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-charcoal-800 group-hover:text-primary-600">{{ lead.name || lead.email }}</p>
              <p class="text-xs text-charcoal-500 mt-0.5">{{ lead.email || lead.mobile_number || (lead.data as any)?.phone || 'No contact info' }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                :class="getStatusClass(lead.status)"
              >
                {{ lead.status || 'new' }}
              </span>
              <ChevronRightIcon class="h-5 w-5 text-charcoal-400 group-hover:text-primary-600" />
            </div>
          </router-link>
        </div>
      </FormCard>

      <!-- Quick Actions -->
      <FormCard>
        <h2 class="text-lg font-semibold text-charcoal-800 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-2 gap-4">
          <router-link
            to="/admin/products/create"
            class="flex flex-col items-center justify-center p-4 border border-charcoal-200 rounded-lg hover:bg-charcoal-50 hover:border-primary-300 transition-colors group"
          >
            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center mb-2 group-hover:bg-primary-100">
              <PlusIcon class="h-5 w-5" />
            </div>
            <span class="text-sm font-medium text-charcoal-700">New Product</span>
          </router-link>
          <router-link
            to="/admin/partners/create"
            class="flex flex-col items-center justify-center p-4 border border-charcoal-200 rounded-lg hover:bg-charcoal-50 hover:border-primary-300 transition-colors group"
          >
            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center mb-2 group-hover:bg-primary-100">
              <PlusIcon class="h-5 w-5" />
            </div>
            <span class="text-sm font-medium text-charcoal-700">New Partner</span>
          </router-link>
          <router-link
            to="/admin/blogs/create"
            class="flex flex-col items-center justify-center p-4 border border-charcoal-200 rounded-lg hover:bg-charcoal-50 hover:border-primary-300 transition-colors group"
          >
            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center mb-2 group-hover:bg-primary-100">
              <BlogsIcon class="h-5 w-5" />
            </div>
            <span class="text-sm font-medium text-charcoal-700">New Blog</span>
          </router-link>
          <router-link
            to="/admin/forms/create"
            class="flex flex-col items-center justify-center p-4 border border-charcoal-200 rounded-lg hover:bg-charcoal-50 hover:border-primary-300 transition-colors group"
          >
            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center mb-2 group-hover:bg-primary-100">
              <FormsIcon class="h-5 w-5" />
            </div>
            <span class="text-sm font-medium text-charcoal-700">New Form</span>
          </router-link>
        </div>
      </FormCard>
    </div>

    <!-- Recent Items -->
    <div class="grid gap-6 lg:grid-cols-2">
      <!-- Recent Products -->
      <FormCard>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-charcoal-800">Recent Products</h2>
          <router-link
            to="/admin/products"
            class="text-sm text-primary-600 hover:text-primary-700 font-medium"
          >
            View All
          </router-link>
        </div>
        <LoadingSpinner v-if="loadingProducts" text="Loading products..." />
        <div v-else-if="recentProducts.length === 0" class="text-sm text-charcoal-500 py-8 text-center">
          No products yet
        </div>
        <div v-else class="space-y-3">
          <router-link
            v-for="product in recentProducts"
            :key="product.id"
            :to="`/admin/products/${product.id}/edit`"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-charcoal-50 transition-colors group"
          >
            <div v-if="product.image" class="flex-shrink-0">
              <img
                :src="`/storage/${product.image}`"
                :alt="product.name"
                loading="lazy"
                class="w-12 h-12 object-cover rounded-lg border border-charcoal-200"
              />
            </div>
            <div v-else class="flex-shrink-0 w-12 h-12 bg-charcoal-100 rounded-lg flex items-center justify-center">
              <svg class="h-6 w-6 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-charcoal-800 group-hover:text-primary-600">{{ product.name }}</p>
              <p class="text-xs text-charcoal-500 mt-0.5">{{ formatTime(product.created_at) }}</p>
            </div>
            <ChevronRightIcon class="h-5 w-5 text-charcoal-400 group-hover:text-primary-600" />
          </router-link>
        </div>
      </FormCard>

      <!-- Recent Activities -->
      <FormCard>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-charcoal-800">Recent Activities</h2>
          <router-link
            to="/admin/activity"
            class="text-sm text-primary-600 hover:text-primary-700 font-medium"
          >
            View All
          </router-link>
        </div>
        <LoadingSpinner v-if="loadingActivity" text="Loading activity..." />
        <div v-else-if="recentActivities.length === 0" class="text-sm text-charcoal-500 py-8 text-center">
          No recent activity
        </div>
        <div v-else class="space-y-4">
          <div
            v-for="activity in recentActivities"
            :key="activity.id"
            class="flex items-start gap-3 pb-4 border-b border-charcoal-100 last:border-0 last:pb-0"
          >
            <div class="flex-shrink-0 mt-0.5">
              <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="getActivityIconClass(activity.log_name)">
                <component :is="getActivityIcon(activity.log_name)" class="h-4 w-4" />
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-charcoal-800">{{ activity.description }}</p>
              <div class="flex items-center gap-2 mt-1">
                <span class="text-xs text-charcoal-500">{{ activity.causer?.name || 'System' }}</span>
                <span class="text-xs text-charcoal-400">•</span>
                <span class="text-xs text-charcoal-500">{{ formatTime(activity.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>
      </FormCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useProductsStore, useLeadsStore, usePartnersStore, useUsersStore, useActivityStore } from '../stores';
import { adminApi } from '../services/api';
import PageHeader from '../components/PageHeader.vue';
import FormCard from '../components/FormCard.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { ProductsIcon, PartnersIcon, UsersIcon, LeadsIcon, FormsIcon, BlogsIcon, InfoIcon, ChevronRightIcon, PlusIcon } from '../components/icons';
import type { Product, Lead, ActivityLog } from '../types/index';

const productsStore = useProductsStore();
const leadsStore = useLeadsStore();
const partnersStore = usePartnersStore();
const usersStore = useUsersStore();
const activityStore = useActivityStore();

const userName = ref<string>('Admin');

interface Stats {
  products: number;
  leads: number;
  partners: number;
  users: number;
}

const stats = ref<Stats>({
  products: 0,
  leads: 0,
  partners: 0,
  users: 0,
});

const recentProducts = ref<Product[]>([]);
const recentLeads = ref<Lead[]>([]);
const recentActivities = ref<ActivityLog[]>([]);

const loadingProducts = ref<boolean>(false);
const loadingLeads = ref<boolean>(false);
const loadingActivity = ref<boolean>(false);

const formatTime = (dateString: string | null | undefined): string => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const now = new Date();
  const diffMs = now.getTime() - date.getTime();
  const diffMins = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMs / 3600000);
  const diffDays = Math.floor(diffMs / 86400000);

  if (diffMins < 1) return 'Just now';
  if (diffMins < 60) return `${diffMins}m ago`;
  if (diffHours < 24) return `${diffHours}h ago`;
  if (diffDays < 7) return `${diffDays}d ago`;
  return date.toLocaleDateString();
};

const getInitials = (name: string | null | undefined): string => {
  if (!name) return '??';
  const parts = name.split(' ');
  if (parts.length >= 2 && parts[0] && parts[1]) {
    return (parts[0][0]! + parts[1][0]!).toUpperCase();
  }
  return name.substring(0, 2).toUpperCase();
};

const getStatusClass = (status: string | null | undefined): string => {
  const classes: Record<string, string> = {
    new: 'bg-blue-100 text-blue-800',
    contacted: 'bg-yellow-100 text-yellow-800',
    converted: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
  };
  const statusLower = (status?.toLowerCase() || 'new') as string;
  return (classes[statusLower] || classes.new) as string;
};

const getActivityIcon = (logName: string | null | undefined): any => {
  const icons: Record<string, any> = {
    products: ProductsIcon,
    partners: PartnersIcon,
    users: UsersIcon,
    leads: LeadsIcon,
    forms: FormsIcon,
    blogs: BlogsIcon,
  };
  return icons[logName || ''] || InfoIcon;
};

const getActivityIconClass = (logName: string | null | undefined): string => {
  const classes: Record<string, string> = {
    products: 'bg-primary-50 text-primary-600',
    partners: 'bg-blue-50 text-blue-600',
    users: 'bg-purple-50 text-purple-600',
    leads: 'bg-green-50 text-green-600',
    forms: 'bg-yellow-50 text-yellow-600',
    blogs: 'bg-indigo-50 text-indigo-600',
  };
  return classes[logName || ''] || 'bg-charcoal-50 text-charcoal-600';
};

const fetchStats = async (): Promise<void> => {
  try {
    // Optimized: Use dedicated stats endpoint for better performance
    // Single API call instead of 4 separate pagination queries
    const response = await adminApi.stats.index();
    const statsData = (response.data as any)?.data || response.data;

    stats.value.products = statsData.products || 0;
    stats.value.leads = statsData.leads || 0;
    stats.value.partners = statsData.partners || 0;
    stats.value.users = statsData.users || 0;
  } catch (error: any) {
    console.error('Error fetching stats:', error);
    // Fallback to individual store fetches if stats endpoint fails
    try {
      await Promise.all([
        productsStore.fetchItems({ per_page: 1, page: 1 }),
        leadsStore.fetchItems({ per_page: 1, page: 1 }),
        partnersStore.fetchItems({ per_page: 1, page: 1 }),
        usersStore.fetchItems({ per_page: 1, page: 1 }),
      ]);
      stats.value.products = productsStore.pagination.total || 0;
      stats.value.leads = leadsStore.pagination.total || 0;
      stats.value.partners = partnersStore.pagination.total || 0;
      stats.value.users = usersStore.pagination.total || 0;
    } catch (fallbackError: any) {
      console.error('Error in fallback stats fetch:', fallbackError);
    }
  }
};

const fetchRecentProducts = async (): Promise<void> => {
  loadingProducts.value = true;
  try {
    await productsStore.fetchItems({ per_page: 5, sort: 'created_at', dir: 'desc' });
    recentProducts.value = productsStore.items.slice(0, 5);
  } catch (error: any) {
    console.error('Error fetching recent products:', error);
  } finally {
    loadingProducts.value = false;
  }
};

const fetchRecentLeads = async (): Promise<void> => {
  loadingLeads.value = true;
  try {
    await leadsStore.fetchItems({ per_page: 50, status: 'new' });
    // Filter to only show "new" leads and take first 5
    recentLeads.value = leadsStore.items
      .filter((lead) => (lead.status || 'new').toLowerCase() === 'new')
      .slice(0, 5);
  } catch (error: any) {
    console.error('Error fetching recent leads:', error);
  } finally {
    loadingLeads.value = false;
  }
};

const fetchRecentActivity = async (): Promise<void> => {
  loadingActivity.value = true;
  try {
    await activityStore.fetchItems({ per_page: 5 });
    recentActivities.value = activityStore.items.slice(0, 5);
  } catch (error: any) {
    console.error('Error fetching recent activity:', error);
  } finally {
    loadingActivity.value = false;
  }
};

onMounted(async () => {
  // Fetch user name from auth store or API
  // For now, using placeholder
  await Promise.all([fetchStats(), fetchRecentProducts(), fetchRecentLeads(), fetchRecentActivity()]);
});
</script>
