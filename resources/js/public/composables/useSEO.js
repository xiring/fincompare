/**
 * SEO composable for dynamic meta tag management
 * Provides a centralized way to manage SEO meta tags across pages
 */

import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useHead } from '@vueuse/head';
import { useSiteSettings } from './useSiteSettings';

/**
 * Get full URL for images/logos
 * @param {string|null} path - Image path
 * @returns {string|null} Full URL or null
 */
function getFullUrl(path) {
  if (!path) return null;
  if (path.startsWith('http://') || path.startsWith('https://')) {
    return path;
  }
  if (path.startsWith('/storage') || path.startsWith('/')) {
    return path.startsWith('/') && !path.startsWith('//')
      ? `${window.location.origin}${path}`
      : path;
  }
  return `${window.location.origin}/storage/${path}`;
}

/**
 * Strip HTML tags and get plain text excerpt
 * @param {string} html - HTML content
 * @param {number} maxLength - Maximum length
 * @returns {string} Plain text excerpt
 */
function getExcerpt(html, maxLength = 160) {
  if (!html) return '';
  const text = html.replace(/<[^>]*>/g, '').trim();
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

/**
 * SEO composable
 * @param {Object} options - SEO options
 * @param {string} options.title - Page title
 * @param {string} options.description - Page description
 * @param {string} options.image - Page image URL
 * @param {string} options.type - Open Graph type (default: 'website')
 * @param {string} options.keywords - Page keywords
 * @param {boolean} options.noindex - Whether to noindex the page
 * @returns {Object} SEO utilities
 */
export function useSEO(options = {}) {
  let route;
  try {
    route = useRoute();
  } catch (e) {
    // Route might not be available in some contexts
    console.warn('useRoute() not available in useSEO:', e);
    route = null;
  }
  const { siteSettings } = useSiteSettings();

  const siteName = computed(() => siteSettings.value?.site_name || 'FinCompare');
  const siteDescription = computed(() => siteSettings.value?.seo_description || 'Compare financial products and find the best deals.');
  const siteKeywords = computed(() => siteSettings.value?.seo_keywords || 'financial comparison, loans, credit cards');
  const siteLogo = computed(() => {
    if (siteSettings.value?.logo) {
      return getFullUrl(siteSettings.value.logo);
    }
    return null;
  });
  const siteUrl = computed(() => window.location.origin);

  // Computed SEO values
  const pageTitle = computed(() => {
    if (options.title) {
      return options.title.includes(siteName.value)
        ? options.title
        : `${options.title} - ${siteName.value}`;
    }
    return siteName.value;
  });

  const pageDescription = computed(() => {
    if (options.description) {
      return getExcerpt(options.description, 160);
    }
    return siteDescription.value;
  });

  const pageKeywords = computed(() => {
    if (options.keywords) {
      return Array.isArray(options.keywords)
        ? [...options.keywords, siteKeywords.value].join(', ')
        : `${options.keywords}, ${siteKeywords.value}`;
    }
    return siteKeywords.value;
  });

  const pageImage = computed(() => {
    if (options.image) {
      return getFullUrl(options.image);
    }
    return siteLogo.value || `${siteUrl.value}/images/default-social-share.jpg`;
  });

  const pageUrl = computed(() => {
    // Safely access route.fullPath with proper null/undefined checks
    try {
      if (route && typeof route.fullPath !== 'undefined') {
        return siteUrl.value + route.fullPath;
      }
    } catch (e) {
      // Route might be undefined or not accessible
      console.warn('Route not accessible in pageUrl computed:', e);
    }
    // Fallback to window.location if route is not available
    const path = window.location.pathname + window.location.search;
    return siteUrl.value + (path || '/');
  });

  const ogType = computed(() => options.type || 'website');

  // Update head tags
  useHead({
    title: pageTitle.value,
    meta: [
      // Basic SEO
      {
        name: 'description',
        content: pageDescription.value
      },
      {
        name: 'keywords',
        content: pageKeywords.value
      },
      {
        name: 'author',
        content: siteName.value
      },
      {
        name: 'robots',
        content: options.noindex ? 'noindex, nofollow' : 'index, follow'
      },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1, shrink-to-fit=no'
      },
      // Open Graph / Facebook
      {
        property: 'og:type',
        content: ogType.value
      },
      {
        property: 'og:url',
        content: pageUrl.value
      },
      {
        property: 'og:title',
        content: pageTitle.value
      },
      {
        property: 'og:description',
        content: pageDescription.value
      },
      {
        property: 'og:image',
        content: pageImage.value
      },
      {
        property: 'og:site_name',
        content: siteName.value
      },
      // Twitter Card
      {
        name: 'twitter:card',
        content: 'summary_large_image'
      },
      {
        name: 'twitter:url',
        content: pageUrl.value
      },
      {
        name: 'twitter:title',
        content: pageTitle.value
      },
      {
        name: 'twitter:description',
        content: pageDescription.value
      },
      {
        name: 'twitter:image',
        content: pageImage.value
      }
    ],
    link: [
      {
        rel: 'canonical',
        href: pageUrl.value
      }
    ]
  });

  return {
    siteName,
    siteDescription,
    siteKeywords,
    siteLogo,
    siteUrl,
    pageTitle,
    pageDescription,
    pageKeywords,
    pageImage,
    pageUrl
  };
}

