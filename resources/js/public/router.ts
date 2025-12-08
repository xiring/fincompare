import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import type { SiteSettings } from './types/index';

// Import pages directly (eager loading)
import Home from './pages/Home.vue';
import About from './pages/About.vue';
import Contact from './pages/Contact.vue';
import Privacy from './pages/Privacy.vue';
import Terms from './pages/Terms.vue';
import Faq from './pages/Faq.vue';
import BlogIndex from './pages/blog/Index.vue';
import BlogShow from './pages/blog/Show.vue';
import ProductsIndex from './pages/products/Index.vue';
import ProductShow from './pages/products/Show.vue';
import Compare from './pages/products/Compare.vue';
import Lead from './pages/Lead.vue';

// PublicRouteMeta interface removed - using RouteMeta directly

declare global {
  interface Window {
    __SITE_SETTINGS__?: SiteSettings;
  }
}

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { title: 'Home' },
  },
  {
    path: '/about',
    name: 'about',
    component: About,
    meta: { title: 'About Us' },
  },
  {
    path: '/contact',
    name: 'contact',
    component: Contact,
    meta: { title: 'Contact Us' },
  },
  {
    path: '/privacy',
    name: 'privacy',
    component: Privacy,
    meta: { title: 'Privacy Policy' },
  },
  {
    path: '/terms',
    name: 'terms',
    component: Terms,
    meta: { title: 'Terms of Service' },
  },
  {
    path: '/faq',
    name: 'faq',
    component: Faq,
    meta: { title: 'FAQ' },
  },
  {
    path: '/blog',
    name: 'blog.index',
    component: BlogIndex,
    meta: { title: 'Blog' },
  },
  {
    path: '/blog/:slug',
    name: 'blog.show',
    component: BlogShow,
    meta: { title: 'Blog Post' },
  },
  {
    path: '/products',
    name: 'products.public.index',
    component: ProductsIndex,
    meta: { title: 'Products' },
  },
  {
    path: '/products/:slug',
    name: 'products.public.show',
    component: ProductShow,
    meta: { title: 'Product Details' },
  },
  {
    path: '/categories/:slug',
    redirect: (to) => {
      return { path: '/products', query: { category: to.params.slug } };
    },
  },
  {
    path: '/compare',
    name: 'compare',
    component: Compare,
    meta: { title: 'Compare Products' },
  },
  {
    path: '/lead',
    name: 'leads.create',
    component: Lead,
    meta: { title: 'Get Started' },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: Home,
    meta: { title: '404 - Not Found' },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, _from, savedPosition) {
    // If there's a saved position (browser back/forward), use it
    if (savedPosition) {
      return savedPosition;
    }
    // If there's a hash in the URL, scroll to that element
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' };
    }
    // For all other cases, scroll to top immediately
    // Use 'auto' instead of 'instant' for better browser compatibility
    return { top: 0, left: 0, behavior: 'auto' };
  },
});

// Update document title on route change
router.beforeEach((to, _from, next) => {
  const siteSettings = window.__SITE_SETTINGS__;
  const baseTitle = siteSettings?.site_name || 'FinCompare';
  const meta = to.meta;
  document.title = meta.title ? `${meta.title} - ${baseTitle}` : baseTitle;
  next();
});

// Ensure home page always starts at top
router.afterEach((to, _from) => {
  if (to.path === '/' && !to.hash) {
    // Use setTimeout to ensure DOM is fully rendered
    setTimeout(() => {
      window.scrollTo({ top: 0, left: 0, behavior: 'auto' });
    }, 100);
  }
});

export default router;

