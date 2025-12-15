import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createHead } from '@vueuse/head';
import { VueQueryPlugin, QueryClient } from '@tanstack/vue-query';
import router from './router';
import App from './App.vue';

// Import base CSS
import '../../css/app.css';

// Create Vue app
const app = createApp(App);

// Create plugins
const pinia = createPinia();
const head = createHead();
const queryClient = new QueryClient();

declare global {
  interface Window {
    __TANSTACK_QUERY_CLIENT__?: QueryClient;
  }
}

if (import.meta.env.DEV && typeof window !== 'undefined') {
  window.__TANSTACK_QUERY_CLIENT__ = queryClient;
}

// Use plugins
app.use(pinia);
app.use(router);
app.use(head);
app.use(VueQueryPlugin, { queryClient });

// Bootstrap axios configuration
import '../bootstrap';

// Mount app
app.mount('#app');

