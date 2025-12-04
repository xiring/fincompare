<template>
  <footer class="bg-[var(--brand-bg)] border-t">
    <div class="h-1 bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand Info -->
        <div>
          <div class="flex items-center gap-2">
            <div v-if="siteSettings?.logo" class="h-8 w-8 rounded-lg overflow-hidden flex items-center justify-center bg-white">
              <img
                :src="getLogoUrl(siteSettings.logo)"
                :alt="siteSettings?.site_name || 'Logo'"
                class="block h-8 w-auto object-contain"
              />
            </div>
            <div v-else class="h-8 w-8 rounded-lg bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]">
              <ApplicationLogo class="block h-8 w-auto fill-current text-gray-800" />
            </div>
            <div class="text-lg font-semibold text-gray-900">{{ siteSettings?.site_name || 'FinCompare' }}</div>
          </div>
          <p class="mt-3 text-sm text-gray-600">{{ siteSettings?.site_slogon || 'Compare Financial Products' }}</p>

          <!-- Social Links -->
          <div class="mt-4 flex items-center gap-3 text-gray-500">
            <a
              v-if="siteSettings?.twitter_url"
              :href="siteSettings.twitter_url"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Twitter"
              class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition"
            >
              <TwitterIcon />
            </a>
            <a
              v-if="siteSettings?.instgram_url"
              :href="siteSettings.instgram_url"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Instagram"
              class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition"
            >
              <InstagramIcon />
            </a>
            <a
              v-if="siteSettings?.facebook_url"
              :href="siteSettings.facebook_url"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Facebook"
              class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition"
            >
              <FacebookIcon />
            </a>
            <a
              v-if="siteSettings?.email_address"
              :href="`mailto:${siteSettings.email_address}`"
              target="_blank"
              aria-label="Email"
              class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition"
            >
              <EmailIcon />
            </a>
          </div>
        </div>

        <!-- Products -->
        <div>
          <div class="text-sm font-semibold text-gray-900">Products</div>
          <ul class="mt-3 space-y-2 text-sm text-gray-600">
            <li><router-link to="/products" class="hover:text-[color:var(--brand-primary)]">Browse products</router-link></li>
            <li><router-link to="/compare" class="hover:text-[color:var(--brand-primary)]">Compare</router-link></li>
            <li><router-link to="/lead" class="hover:text-[color:var(--brand-primary)]">Send inquiry</router-link></li>
          </ul>
        </div>

        <!-- Company -->
        <div>
          <div class="text-sm font-semibold text-gray-900">Company</div>
          <ul class="mt-3 space-y-2 text-sm text-gray-600">
            <li><router-link to="/about" class="hover:text-[color:var(--brand-primary)]">About</router-link></li>
            <li><router-link to="/contact" class="hover:text-[color:var(--brand-primary)]">Contact</router-link></li>
          </ul>
        </div>

        <!-- Resources -->
        <div>
          <div class="text-sm font-semibold text-gray-900">Resources</div>
          <ul class="mt-3 space-y-2 text-sm text-gray-600">
            <li><router-link to="/faq" class="hover:text-[color:var(--brand-primary)]">Help center</router-link></li>
            <li><router-link to="/blog" class="hover:text-[color:var(--brand-primary)]">Blog</router-link></li>
          </ul>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="mt-8 pt-6 border-t text-xs text-gray-600 flex flex-col md:flex-row items-center justify-between gap-2">
        <div>
          &copy; {{ currentYear }} {{ siteSettings?.site_name || 'FinCompare' }}. All rights reserved.
        </div>
        <div class="flex items-center gap-4">
          <router-link to="/privacy" class="hover:text-[color:var(--brand-primary)]">Privacy</router-link>
          <router-link to="/terms" class="hover:text-[color:var(--brand-primary)]">Terms</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';
import { useSiteSettings } from '../composables/useSiteSettings';
import { getLogoUrl } from '../utils/helpers';
import { TwitterIcon, InstagramIcon, FacebookIcon, EmailIcon } from './icons';
import ApplicationLogo from './ApplicationLogo.vue';

const { siteSettings } = useSiteSettings();
const currentYear = computed(() => new Date().getFullYear());
</script>
