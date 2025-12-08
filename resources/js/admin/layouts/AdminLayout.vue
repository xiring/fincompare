<template>
  <div class="min-h-screen bg-charcoal-50 flex flex-col">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'lg:translate-x-0',
        sidebarCollapsed ? 'lg:w-16' : 'lg:w-64',
        'w-64'
      ]"
      class="bg-charcoal-800 border-r border-charcoal-700"
    >
      <!-- Logo -->
      <div class="flex items-center justify-between h-16 px-4 border-b border-charcoal-700">
        <div class="flex items-center min-w-0">
          <div class="h-8 w-8 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <span v-show="!sidebarCollapsed" class="ml-3 text-xl font-bold text-white whitespace-nowrap overflow-hidden">FinCompare</span>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
          <button
            @click="toggleSidebar"
            class="hidden lg:flex text-charcoal-300 hover:text-white p-1 rounded transition-colors"
            title="Toggle sidebar"
          >
            <ChevronRightIcon v-if="sidebarCollapsed" class="h-5 w-5" />
            <ChevronLeftIcon v-else class="h-5 w-5" />
          </button>
          <button
            @click="sidebarOpen = false"
            class="lg:hidden text-charcoal-300 hover:text-white p-1 rounded transition-colors"
          >
            <XIcon class="h-6 w-6" />
          </button>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="px-2 py-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem-4rem)]">
        <!-- Dashboard -->
        <router-link
          to="/admin"
          class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
          :class="isActive('/admin') && route.path === '/admin'
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          :title="sidebarCollapsed ? 'Dashboard' : ''"
        >
          <DashboardIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
          <span v-show="!sidebarCollapsed" class="truncate">Dashboard</span>
        </router-link>

        <!-- Partners -->
        <router-link
          to="/admin/partners"
          class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
          :class="isActive('/admin/partners')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          :title="sidebarCollapsed ? 'Partners' : ''"
        >
          <PartnersIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
          <span v-show="!sidebarCollapsed" class="truncate">Partners</span>
        </router-link>

        <!-- Catalog Dropdown -->
        <div class="space-y-1">
          <button
            @click="catalogOpen = !catalogOpen"
            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
            :class="isCatalogActive()
              ? 'bg-primary-500/20 text-primary-300'
              : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
            :title="sidebarCollapsed ? 'Catalog' : ''"
          >
            <div class="flex items-center min-w-0">
              <CatalogIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
              <span v-show="!sidebarCollapsed" class="truncate">Catalog</span>
            </div>
            <ChevronDownIcon
              v-show="!sidebarCollapsed"
              class="h-4 w-4 transition-transform flex-shrink-0"
              :class="{ 'rotate-180': catalogOpen }"
            />
          </button>
          <div v-show="catalogOpen && !sidebarCollapsed" class="ml-8 space-y-1 border-l-2 border-charcoal-700 pl-4">
            <router-link
              to="/admin/forms"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/forms')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <FormsIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Forms
            </router-link>
            <router-link
              to="/admin/product-categories"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/product-categories')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <CategoriesIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Categories
            </router-link>
            <router-link
              to="/admin/products"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/products')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <ProductsIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Products
            </router-link>
            <router-link
              to="/admin/attributes"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/attributes')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <AttributesIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Attributes
            </router-link>
          </div>
        </div>

        <!-- Leads -->
        <router-link
          to="/admin/leads"
          class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
          :class="isActive('/admin/leads')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          :title="sidebarCollapsed ? 'Leads' : ''"
        >
          <LeadsIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
          <span v-show="!sidebarCollapsed" class="truncate">Leads</span>
        </router-link>

        <!-- Activity -->
        <router-link
          to="/admin/activity"
          class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
          :class="isActive('/admin/activity')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          :title="sidebarCollapsed ? 'Activity' : ''"
        >
          <ActivityIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
          <span v-show="!sidebarCollapsed" class="truncate">Activity</span>
        </router-link>

        <!-- Access Dropdown (Admin Only) -->
        <div v-if="isAdmin" class="space-y-1">
          <button
            @click="accessOpen = !accessOpen"
            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
            :class="isAccessActive()
              ? 'bg-primary-500/20 text-primary-300'
              : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
            :title="sidebarCollapsed ? 'Access' : ''"
          >
            <div class="flex items-center min-w-0">
              <AccessIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
              <span v-show="!sidebarCollapsed" class="truncate">Access</span>
            </div>
            <ChevronDownIcon
              v-show="!sidebarCollapsed"
              class="h-4 w-4 transition-transform flex-shrink-0"
              :class="{ 'rotate-180': accessOpen }"
            />
          </button>
          <div v-show="accessOpen && !sidebarCollapsed" class="ml-8 space-y-1 border-l-2 border-charcoal-700 pl-4">
            <router-link
              to="/admin/users"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/users')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <UsersIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Users
            </router-link>
            <router-link
              to="/admin/roles"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/roles')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <RolesIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Roles
            </router-link>
            <router-link
              to="/admin/permissions"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/permissions')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <PermissionsIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Permissions
            </router-link>
          </div>
        </div>

        <!-- Content Dropdown -->
        <div class="space-y-1">
          <button
            @click="contentOpen = !contentOpen"
            class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
            :class="isContentActive()
              ? 'bg-primary-500/20 text-primary-300'
              : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
            :title="sidebarCollapsed ? 'Content' : ''"
          >
            <div class="flex items-center min-w-0">
              <ContentIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
              <span v-show="!sidebarCollapsed" class="truncate">Content</span>
            </div>
            <ChevronDownIcon
              v-show="!sidebarCollapsed"
              class="h-4 w-4 transition-transform flex-shrink-0"
              :class="{ 'rotate-180': contentOpen }"
            />
          </button>
          <div v-show="contentOpen && !sidebarCollapsed" class="ml-8 space-y-1 border-l-2 border-charcoal-700 pl-4">
            <router-link
              to="/admin/blogs"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/blogs')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <BlogsIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              Blogs
            </router-link>
            <router-link
              to="/admin/cms-pages"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/cms-pages')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <CmsPagesIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              CMS Pages
            </router-link>
            <router-link
              to="/admin/faqs"
              class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors"
              :class="isActive('/admin/faqs')
                ? 'bg-primary-500/20 text-primary-300 font-medium'
                : 'text-charcoal-400 hover:bg-charcoal-700 hover:text-white'"
            >
              <FaqsIcon class="h-4 w-4 mr-2 flex-shrink-0" />
              FAQs
            </router-link>
          </div>
        </div>

        <!-- Settings -->
        <router-link
          to="/admin/settings"
          class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors group"
          :class="isActive('/admin/settings')
            ? 'bg-primary-500/20 text-primary-300'
            : 'text-charcoal-300 hover:bg-charcoal-700 hover:text-white'"
          :title="sidebarCollapsed ? 'Settings' : ''"
        >
          <SettingsIcon class="h-5 w-5 flex-shrink-0" :class="sidebarCollapsed ? '' : 'mr-3'" />
          <span v-show="!sidebarCollapsed" class="truncate">Settings</span>
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
    <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarCollapsed ? 'lg:pl-16' : 'lg:pl-64'">
      <!-- Header -->
      <header class="sticky top-0 z-30 bg-white border-b border-charcoal-200">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
          <div class="flex items-center gap-3">
            <button
              @click="sidebarOpen = true"
              class="lg:hidden text-charcoal-500 hover:text-charcoal-700 p-2 rounded transition-colors"
            >
              <MenuIcon class="h-6 w-6" />
            </button>
            <button
              @click="toggleSidebar"
              class="hidden lg:flex text-charcoal-500 hover:text-charcoal-700 p-2 rounded transition-colors"
              title="Toggle sidebar"
            >
              <ChevronRightIcon v-if="sidebarCollapsed" class="h-5 w-5" />
              <ChevronLeftIcon v-else class="h-5 w-5" />
            </button>
          </div>

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
                <ChevronDownIcon class="h-5 w-5 text-charcoal-500" />
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
      <main class="flex-1 p-4 sm:p-6 lg:p-8 pb-20">
        <RouterView />
      </main>

      <!-- Footer -->
      <footer class="bg-white border-t border-charcoal-200 py-4 px-4 sm:px-6 lg:px-8 mt-auto">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="text-sm text-charcoal-600">
            <p>&copy; {{ currentYear }} FinCompare. All rights reserved.</p>
          </div>
          <div class="flex items-center gap-6 text-sm text-charcoal-600">
            <a href="#" class="hover:text-primary-600 transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-primary-600 transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-primary-600 transition-colors">Support</a>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute, useRouter, RouterView } from 'vue-router';
import axios from 'axios';
import {
  DashboardIcon,
  PartnersIcon,
  CatalogIcon,
  FormsIcon,
  CategoriesIcon,
  ProductsIcon,
  AttributesIcon,
  LeadsIcon,
  ActivityIcon,
  AccessIcon,
  UsersIcon,
  RolesIcon,
  PermissionsIcon,
  ContentIcon,
  BlogsIcon,
  CmsPagesIcon,
  FaqsIcon,
  SettingsIcon,
  ChevronDownIcon,
  ChevronRightIcon,
  ChevronLeftIcon,
  XIcon,
  MenuIcon
} from '../components/icons';

const route = useRoute();
const router = useRouter();

const sidebarOpen = ref(false);
const sidebarCollapsed = ref(false);
const userMenuOpen = ref(false);
const userMenuRef = ref(null);
const catalogOpen = ref(false);
const accessOpen = ref(false);
const contentOpen = ref(false);
const user = ref({ name: 'Admin User', email: 'admin@example.com' });
const isAdmin = ref(true); // TODO: Get from user data
const currentYear = ref(new Date().getFullYear());

// Load sidebar state from localStorage
const loadSidebarState = () => {
  const saved = localStorage.getItem('sidebarCollapsed');
  if (saved !== null) {
    sidebarCollapsed.value = saved === 'true';
  }
};

// Save sidebar state to localStorage
const saveSidebarState = () => {
  localStorage.setItem('sidebarCollapsed', sidebarCollapsed.value.toString());
};

// Toggle sidebar collapse
const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
  saveSidebarState();
  // Close dropdowns when collapsing
  if (sidebarCollapsed.value) {
    catalogOpen.value = false;
    accessOpen.value = false;
    contentOpen.value = false;
  }
};

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
  loadSidebarState();
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

