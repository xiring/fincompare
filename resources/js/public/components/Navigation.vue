<template>
  <nav class="sticky top-0 z-40 transition bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="shrink-0 flex items-center">
            <router-link to="/" class="flex items-center">
              <img
                v-if="siteSettings?.logo"
                :src="getLogoUrl(siteSettings.logo)"
                :alt="siteSettings?.site_name || 'Logo'"
                class="block h-9 w-auto object-contain"
              />
              <ApplicationLogo
                v-else
                class="block h-9 w-auto fill-current text-[var(--brand-text)]"
              />
            </router-link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-stretch">
            <!-- Products Mega Menu -->
            <div
              class="relative"
              @mouseenter="navStore.setMegaMenu('products')"
              @mouseleave="navStore.closeMegaMenu()"
            >
              <button
                type="button"
                :aria-expanded="navStore.megaMenu === 'products'"
                aria-label="Products menu"
                class="h-full inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[var(--brand-text)] hover:text-black hover:border-white/40 focus:outline-none focus:ring-2 focus:ring-white/50"
              >
                Products
              </button>

              <!-- Mega panel -->
              <div
                v-show="navStore.megaMenu === 'products'"
                @mouseenter="navStore.setMegaMenu('products')"
                @mouseleave="navStore.closeMegaMenu()"
                class="fixed left-0 right-0 top-16 w-full pt-3 z-50"
              >
                <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                  <div class="rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                      <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Loans & Cards</div>
                        <ul class="space-y-2 text-sm">
                          <li><router-link to="/products" class="hover:text-[color:var(--brand-primary)]">Personal Loans</router-link></li>
                          <li><router-link to="/products" class="hover:text-[color:var(--brand-primary)]">Business Loans</router-link></li>
                          <li><router-link to="/products" class="hover:text-[color:var(--brand-primary)]">Credit Cards</router-link></li>
                          <li><router-link to="/products" class="hover:text-[color:var(--brand-primary)]">Home Loans</router-link></li>
                        </ul>
                      </div>
                      <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Investments & Insurance</div>
                        <ul class="space-y-2 text-sm">
                          <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">Fixed Deposits</router-link></li>
                          <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">Bonds</router-link></li>
                          <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">Health Insurance</router-link></li>
                          <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">Term Insurance</router-link></li>
                        </ul>
                      </div>
                      <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tools & Calculators</div>
                        <ul class="space-y-2 text-sm">
                          <li><router-link to="/compare" class="hover:text-[color:var(--brand-primary)]">Compare Products</router-link></li>
                          <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">EMI Calculators</router-link></li>
                          <li><router-link to="/faq" class="hover:text-[color:var(--brand-primary)]">FAQs</router-link></li>
                        </ul>
                      </div>
                      <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Learn & Support</div>
                        <ul class="space-y-2 text-sm">
                          <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">Blog</router-link></li>
                          <li><router-link to="/about" class="hover:text-[color:var(--brand-primary)]">About Us</router-link></li>
                          <li><router-link to="/contact" class="hover:text-[color:var(--brand-primary)]">Contact</router-link></li>
                          <li><router-link to="/privacy" class="hover:text-[color:var(--brand-primary)]">Privacy</router-link></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <NavLink to="/compare" :active="$route.path === '/compare'">Compare</NavLink>
            <NavLink to="/blog" :active="$route.path.startsWith('/blog')">Blog</NavLink>
            <NavLink to="/about" :active="$route.path === '/about'">About Us</NavLink>
            <NavLink to="/contact" :active="$route.path === '/contact'">Contact Us</NavLink>
          </div>
        </div>

        <!-- Right actions -->
        <div class="hidden sm:flex items-center gap-4">
          <!-- Talk to Expert -->
          <div
            class="relative"
            @mouseenter="navStore.openExpertPopover()"
            @mouseleave="navStore.closeExpertPopover()"
          >
            <button
              type="button"
              :aria-expanded="navStore.expertPopoverOpen"
              aria-label="Talk to Expert"
              class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium text-[var(--brand-text)] hover:text-black hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50"
            >
              <PhoneIcon className="h-4 w-4" />
              <span>Talk to Expert</span>
            </button>

            <!-- Popover -->
            <div
              v-show="navStore.expertPopoverOpen"
              class="absolute right-0 top-full mt-2 z-50"
              style="width: 36rem; max-width: calc(100vw - 2rem); min-width: 28rem;"
              @mouseenter="navStore.openExpertPopover()"
              @mouseleave="navStore.closeExpertPopover()"
            >
              <div class="relative w-full rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/10 p-5">
                <div class="flex items-center gap-2 mb-3">
                  <PhoneIcon className="h-5 w-5 text-[color:var(--brand-primary)]" />
                  <h3 class="text-base font-semibold">Talk to Expert</h3>
                </div>
                <div class="space-y-3 text-sm">
                  <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sales Enquiry</div>
                    <div class="text-base">Call Us: <a href="tel:18005703888" class="font-semibold text-[color:var(--brand-primary)] hover:underline">1800 570 3888</a></div>
                  </div>
                  <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Service Helpline</div>
                    <div class="text-base">Call Us: <a href="tel:18002585616" class="font-semibold text-[color:var(--brand-primary)] hover:underline">1800 258 5616</a></div>
                  </div>
                  <p class="text-sm text-gray-600">Our advisors are available 7 days a week, <span class="font-semibold">9:30 am - 6:30 pm</span> to assist you with the best offers or help resolve any queries.</p>
                </div>
              </div>
            </div>
          </div>

         <!-- Sign in (placeholder for now - requires auth integration) -->
          <a
            href="/login"
            class="inline-flex items-center rounded-full bg-white/15 px-3 py-1.5 text-sm font-semibold text-[var(--brand-text)] hover:bg-white/25 focus:outline-none"
          >
            Sign in
          </a>
        </div>

        <!-- Mobile menu button -->
        <div class="flex items-center -me-2 sm:hidden">
          <button
            @click="navStore.toggleMobileMenu()"
            type="button"
            class="inline-flex items-center justify-center p-2 rounded-md text-[var(--brand-text)] hover:text-black hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-black"
            :aria-expanded="navStore.mobileMenuOpen"
            aria-label="Toggle navigation menu"
          >
            <span class="sr-only">Open main menu</span>
            <MenuIcon v-show="!navStore.mobileMenuOpen" />
            <CloseIcon v-show="navStore.mobileMenuOpen" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div v-show="navStore.mobileMenuOpen" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1 bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white">
        <router-link to="/" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10" :class="{ 'bg-white/10': $route.path === '/' }">Home</router-link>
        <router-link to="/products" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10" :class="{ 'bg-white/10': $route.path.startsWith('/products') }">Products</router-link>
        <router-link to="/compare" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10" :class="{ 'bg-white/10': $route.path === '/compare' }">Compare</router-link>
        <router-link to="/about" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10" :class="{ 'bg-white/10': $route.path === '/about' }">About Us</router-link>
        <router-link to="/contact" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10" :class="{ 'bg-white/10': $route.path === '/contact' }">Contact Us</router-link>
        <router-link to="/faq" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10" :class="{ 'bg-white/10': $route.path === '/faq' }">FAQ</router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useSiteSettings } from '../composables';
import { useNavigationStore } from '../stores/navigation';
import { getLogoUrl } from '../utils/helpers';
import { PhoneIcon, MenuIcon, CloseIcon } from './icons';
import ApplicationLogo from './ApplicationLogo.vue';
import NavLink from './NavLink.vue';

const route = useRoute();
const { siteSettings } = useSiteSettings();
const navStore = useNavigationStore();

// Close menus on route change
watch(() => route.path, () => {
  navStore.closeAll();
});
</script>
