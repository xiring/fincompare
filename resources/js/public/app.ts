import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createHead } from '@vueuse/head';
import router from './router';
import App from './App.vue';

// Import base CSS
import '../../css/app.css';

// Create Vue app
const app = createApp(App);

// Create plugins
const pinia = createPinia();
const head = createHead();

// Use plugins
app.use(pinia);
app.use(router);
app.use(head);

// Bootstrap axios configuration
import '../bootstrap';

// Mount app
app.mount('#app');

// Ensure page starts at top on initial load
if (window.location.hash === '' || window.location.hash === '#') {
  window.scrollTo(0, 0);
}

