<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 z-40 w-64 h-screen transition-transform',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'lg:translate-x-0'
      ]"
      class="bg-charcoal-800 border-r border-charcoal-700"
    >
      <!-- Logo -->
      <div class="flex items-center justify-between h-16 px-6 border-b border-charcoal-700">
        <div class="flex items-center">
          <div class="h-8 w-8 bg-primary-500 rounded-lg flex items-center justify-center">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <span class="ml-3 text-xl font-bold text-white">FinCompare</span>
        </div>
        <button
          @click="sidebarOpen = false"
          class="lg:hidden text-charcoal-300 hover:text-white"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="px-4 py-6 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
        <!-- Dashboard -->
        <router-link
          to="/admin"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
          :class="isActive('/admin') && route.path === '/admin'
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
        >
          <component :is="DashboardIcon" class="h-5 w-5 mr-3" />
          <span>Dashboard</span>
        </router-link>

        <!-- Partners -->
        <router-link
          to="/admin/partners"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
          :class="isActive('/admin/partners')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
        >
          <component :is="PartnersIcon" class="h-5 w-5 mr-3" />
          <span>Partners</span>
        </router-link>

        <!-- Catalog Dropdown -->
        <div class="space-y-1">
          <button
            @click="catalogOpen = !catalogOpen"
            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            :class="isCatalogActive()
              ? 'bg-primary-500/20 text-primary-300'
              : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          >
            <div class="flex items-center">
              <component :is="CatalogIcon" class="h-5 w-5 mr-3" />
              <span>Catalog</span>
            </div>
            <svg
              class="h-4 w-4 transition-transform"
              :class="{ 'rotate-180': catalogOpen }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div v-show="catalogOpen" class="ml-8 space-y-1 border-l-2 border-charcoal-700 pl-4">
            <router-link
              to="/admin/forms"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/forms')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Forms
            </router-link>
            <router-link
              to="/admin/product-categories"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/product-categories')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Categories
            </router-link>
            <router-link
              to="/admin/products"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/products')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Products
            </router-link>
            <router-link
              to="/admin/attributes"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/attributes')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Attributes
            </router-link>
          </div>
        </div>

        <!-- Leads -->
        <router-link
          to="/admin/leads"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
          :class="isActive('/admin/leads')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
        >
          <component :is="LeadsIcon" class="h-5 w-5 mr-3" />
          <span>Leads</span>
        </router-link>

        <!-- Activity -->
        <router-link
          to="/admin/activity"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
          :class="isActive('/admin/activity')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
        >
          <component :is="ActivityIcon" class="h-5 w-5 mr-3" />
          <span>Activity</span>
        </router-link>

        <!-- Access Dropdown (Admin Only) -->
        <div v-if="isAdmin" class="space-y-1">
          <button
            @click="accessOpen = !accessOpen"
            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            :class="isAccessActive()
              ? 'bg-primary-500/20 text-primary-300'
              : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          >
            <div class="flex items-center">
              <component :is="AccessIcon" class="h-5 w-5 mr-3" />
              <span>Access</span>
            </div>
            <svg
              class="h-4 w-4 transition-transform"
              :class="{ 'rotate-180': accessOpen }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div v-show="accessOpen" class="ml-8 space-y-1 border-l-2 border-charcoal-700 pl-4">
            <router-link
              to="/admin/users"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/users')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Users
            </router-link>
            <router-link
              to="/admin/roles"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/roles')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Roles
            </router-link>
            <router-link
              to="/admin/permissions"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/permissions')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Permissions
            </router-link>
          </div>
        </div>

        <!-- Content Dropdown -->
        <div class="space-y-1">
          <button
            @click="contentOpen = !contentOpen"
            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors"
            :class="isContentActive()
              ? 'bg-primary-500/20 text-primary-300'
              : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          >
            <div class="flex items-center">
              <component :is="ContentIcon" class="h-5 w-5 mr-3" />
              <span>Content</span>
            </div>
            <svg
              class="h-4 w-4 transition-transform"
              :class="{ 'rotate-180': contentOpen }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div v-show="contentOpen" class="ml-8 space-y-1 border-l-2 border-charcoal-700 pl-4">
            <router-link
              to="/admin/blogs"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/blogs')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              Blogs
            </router-link>
            <router-link
              to="/admin/cms-pages"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/cms-pages')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              CMS Pages
            </router-link>
            <router-link
              to="/admin/faqs"
              class="block px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/faqs')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              FAQs
            </router-link>
          </div>
        </div>

        <!-- Settings -->
        <router-link
          to="/admin/settings"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
          :class="isActive('/admin/settings')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
        >
          <component :is="SettingsIcon" class="h-5 w-5 mr-3" />
          <span>Settings</span>
        </router-link>
      </nav>
    </aside>

    <!-- Sidebar Overlay (Mobile) -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-30 bg-charcoal-900 bg-opacity-50 lg:hidden"
    ></div>

    <!-- Main Content -->
    <div class="lg:pl-64">
      <!-- Header -->
      <header class="sticky top-0 z-30 bg-white border-b border-charcoal-200">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
          <button
            @click="sidebarOpen = true"
            class="lg:hidden text-charcoal-500 hover:text-charcoal-700"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <div class="flex-1 flex items-center justify-end gap-4">
            <!-- Notifications -->
            <button class="p-2 text-charcoal-500 hover:text-charcoal-700 relative">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User Menu -->
            <div class="relative" ref="userMenuRef">
              <button
                @click="userMenuOpen = !userMenuOpen"
                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100"
              >
                <div class="h-8 w-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                  {{ userInitials }}
                </div>
                <div class="hidden md:block text-left">
                  <div class="text-sm font-medium text-charcoal-900">{{ userName }}</div>
                  <div class="text-xs text-charcoal-500">{{ userEmail }}</div>
                </div>
                <svg class="h-5 w-5 text-charcoal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Dropdown Menu -->
              <div
                v-if="userMenuOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-charcoal-200 py-1 z-50"
              >
                <router-link
                  to="/admin/profile"
                  class="block px-4 py-2 text-sm text-charcoal-700 hover:bg-gray-100"
                  @click="userMenuOpen = false"
                >
                  Profile
                </router-link>
                <button
                  @click="handleLogout"
                  class="block w-full text-left px-4 py-2 text-sm text-charcoal-700 hover:bg-gray-100"
                >
                  Sign out
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 sm:p-6 lg:p-8">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute, useRouter, RouterView } from 'vue-router';
import axios from 'axios';

// Icons (simplified - you can import from a component library)
const DashboardIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>' };
const PartnersIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 10-8 0v4m-2 4h12a2 2 0 012 2v3H2v-3a2 2 0 012-2z" /></svg>' };
const CatalogIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' };
const LeadsIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' };
const ActivityIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' };
const AccessIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>' };
const ContentIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' };
const SettingsIcon = { template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' };

const route = useRoute();
const router = useRouter();

const sidebarOpen = ref(false);
const userMenuOpen = ref(false);
const userMenuRef = ref(null);
const catalogOpen = ref(false);
const accessOpen = ref(false);
const contentOpen = ref(false);
const user = ref({ name: 'Admin User', email: 'admin@example.com' });
const isAdmin = ref(true); // TODO: Get from user data

const isActive = (path) => {
  if (path === '/admin') {
    return route.path === '/admin';
  }
  return route.path.startsWith(path);
};

const isCatalogActive = () => {
  return isActive('/admin/forms') || isActive('/admin/product-categories') ||
         isActive('/admin/products') || isActive('/admin/attributes');
};

const isAccessActive = () => {
  return isActive('/admin/users') || isActive('/admin/roles') || isActive('/admin/permissions');
};

const isContentActive = () => {
  return isActive('/admin/blogs') || isActive('/admin/cms-pages') || isActive('/admin/faqs');
};

// Set dropdown states based on current route
onMounted(() => {
  if (isCatalogActive()) catalogOpen.value = true;
  if (isAccessActive()) accessOpen.value = true;
  if (isContentActive()) contentOpen.value = true;
});

const userName = computed(() => user.value.name || 'Admin');
const userEmail = computed(() => user.value.email || 'admin@example.com');
const userInitials = computed(() => {
  const name = userName.value;
  const parts = name.split(' ');
  if (parts.length >= 2) {
    return (parts[0][0] + parts[1][0]).toUpperCase();
  }
  return name.substring(0, 2).toUpperCase();
});

const handleLogout = async () => {
  try {
    await axios.post('/logout');
    localStorage.removeItem('authenticated');
    window.location.href = '/login';
  } catch (error) {
    console.error('Logout error:', error);
    localStorage.removeItem('authenticated');
    window.location.href = '/login';
  }
};

// Close user menu when clicking outside
const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    userMenuOpen.value = false;
  }
};

// Watch route changes to update dropdown states
watch(() => route.path, () => {
  if (isCatalogActive()) catalogOpen.value = true;
  if (isAccessActive()) accessOpen.value = true;
  if (isContentActive()) contentOpen.value = true;
});

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  // Fetch user data
  // You can add an API call here to get user info
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Additional styles if needed */
</style>

