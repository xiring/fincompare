<template>
  <GuestLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          <div class="lg:col-span-7 text-center lg:text-left">
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">
              Find and compare the best financial products
            </h1>
            <p class="mt-4 text-white/90 max-w-2xl">
              Loans, cards, and more — compare side-by-side and apply with confidence.
            </p>
            <div class="mt-8 bg-white/10 backdrop-blur rounded-2xl p-3 ring-1 ring-white/20">
              <form @submit.prevent="handleSearch" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                  <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <SearchIcon />
                  </span>
                  <input
                    v-model="searchQuery"
                    placeholder="Search for credit cards, personal loans, ..."
                    class="w-full pl-10 pr-3 py-3 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none"
                  />
                </div>
                <div class="flex gap-3">
                  <router-link
                    to="/products"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-[color:var(--brand-primary)] font-semibold shadow hover:bg-gray-50"
                  >
                    Browse All
                  </router-link>
                  <button
                    class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[color:var(--brand-primary)] text-white font-semibold shadow hover:bg-[color:var(--brand-primary-2)]"
                    type="submit"
                  >
                    Search
                  </button>
                </div>
              </form>
              <div class="mt-3 flex flex-wrap gap-2 text-xs">
                <button
                  @click="handleFilterClick('0% APR')"
                  class="px-3 py-1 rounded-full bg-white/20 hover:bg-white/30 transition-colors cursor-pointer"
                >
                  0% APR
                </button>
                <button
                  @click="handleFilterClick('cashback')"
                  class="px-3 py-1 rounded-full bg-white/20 hover:bg-white/30 transition-colors cursor-pointer"
                >
                  Cashback
                </button>
                <button
                  @click="handleFilterClick('travel')"
                  class="px-3 py-1 rounded-full bg-white/20 hover:bg-white/30 transition-colors cursor-pointer"
                >
                  Travel
                </button>
                <button
                  @click="handleFilterClick('personal loan')"
                  class="px-3 py-1 rounded-full bg-white/20 hover:bg-white/30 transition-colors cursor-pointer"
                >
                  Personal Loans
                </button>
              </div>
            </div>
          </div>
          <div class="lg:col-span-5">
            <div class="relative mx-auto max-w-md">
              <div class="aspect-[4/3] rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center">
                <img src="https://placehold.co/640x480?text=Comparison+Preview" alt="Preview" class="rounded-xl shadow-2xl" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats band -->
    <section
      ref="statsRef"
      :class="statsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-8 bg-white"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div
            v-for="(stat, index) in stats"
            :key="index"
            :style="{ animationDelay: `${index * 100}ms` }"
            :class="statsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="p-5 rounded-2xl border bg-white text-center"
          >
            <div class="text-2xl font-extrabold text-gray-900">
              <span ref="statRefs" :data-target="stat.target" :data-mode="stat.mode">
                {{ stat.display }}
              </span>
            </div>
            <div class="text-xs text-gray-500 mt-1">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories -->
    <section
      ref="categoriesRef"
      :class="categoriesVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-12"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold">Explore Financial Products</h2>
          <router-link to="/products" class="text-sm text-[color:var(--brand-primary)] hover:underline font-medium">
            Browse all
          </router-link>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
          <router-link
            v-for="(category, index) in categories"
            :key="category.id"
            :to="`/categories/${category.slug}`"
            :style="{ animationDelay: `${index * 50}ms` }"
            :class="categoriesVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="group relative bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-lg transition-all duration-300 flex flex-col items-center text-center overflow-visible min-h-[180px]"
          >
            <!-- Offer Banner - Improved styling with better positioning -->
            <div v-if="index < 2" class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10">
              <div class="relative bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-lg shadow-sm whitespace-nowrap">
                {{ index === 1 ? '5% Cashback' : 'Cashback Offer' }}
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full">
                  <div class="w-0 h-0 border-l-[6px] border-r-[6px] border-t-[6px] border-l-transparent border-r-transparent border-t-green-100"></div>
                </div>
              </div>
            </div>
            <!-- Category Icon/Image - Improved design with better proportions -->
            <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 border border-[color:var(--brand-primary)]/10 shadow-sm overflow-hidden flex-shrink-0">
              <img
                v-if="category.image_url"
                :src="getImageUrl(category.image_url)"
                :alt="category.name || 'Category'"
                class="w-full h-full object-cover"
              />
              <DollarIcon class="w-14 h-14 text-[color:var(--brand-primary)]" />
            </div>
            <!-- Category Name -->
            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-[color:var(--brand-primary)] transition-colors mt-auto leading-tight">
              {{ category.name || 'Category' }}
            </h3>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Benefits / Why choose us -->
    <section
      ref="benefitsRef"
      :class="benefitsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-16"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8">Why choose FinCompare</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="(benefit, index) in benefits"
            :key="index"
            :style="{ animationDelay: `${index * 120}ms` }"
            :class="benefitsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="p-6 bg-white border rounded-2xl"
          >
            <div class="h-10 w-10 rounded-lg flex items-center justify-center mb-3 bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] ring-1 ring-[color:var(--brand-primary)]/20">
              <component :is="benefit.icon" class="h-5 w-5" />
            </div>
            <h3 class="font-semibold">{{ benefit.title }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ benefit.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section
      ref="productsRef"
      :class="productsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-16 bg-gray-50"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-6">
          <h2 class="text-2xl font-bold">Editor's Picks</h2>
          <router-link
            :to="{ path: '/products', query: { featured: 1 } }"
            class="text-sm text-[color:var(--brand-primary)] hover:underline"
          >
            See all
          </router-link>
        </div>
        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="i in 3" :key="i" class="p-4 bg-white border rounded-lg shadow-sm animate-pulse">
            <div class="h-48 bg-gray-200 rounded mb-4"></div>
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
          </div>
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="(product, index) in featuredProducts"
            :key="product.id"
            :style="{ animationDelay: `${index * 100}ms` }"
            :class="productsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
          >
            <ProductCard :product="product" />
          </div>
          <div
            v-if="featuredProducts.length === 0"
            class="col-span-full text-center py-12 text-gray-500"
          >
            No featured products available at the moment.
          </div>
        </div>
      </div>
    </section>

    <!-- Partners -->
    <section
      ref="partnersRef"
      :class="partnersVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-12"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold mb-6">Our Trusted Partners</h2>
        <div v-if="loading && validPartners.length === 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 items-center">
          <div v-for="i in 5" :key="i" class="h-12 bg-gray-200 rounded animate-pulse"></div>
        </div>
        <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 items-center">
          <template v-for="(partner, index) in validPartners" :key="partner?.id || index">
            <a
              v-if="partner && partner.website_url"
              :href="partner.website_url"
              target="_blank"
              rel="noopener noreferrer"
              :style="{ animationDelay: `${index * 80}ms` }"
              :class="partnersVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
              class="h-12 flex items-center justify-center bg-white border rounded hover:shadow-md transition-shadow cursor-pointer"
            >
              <img
                :src="partner.logo_url || 'https://placehold.co/120x30?text=Logo'"
                :alt="partner.name || 'Partner'"
                class="max-h-8"
              />
            </a>
            <div
              v-else-if="partner"
              :style="{ animationDelay: `${index * 80}ms` }"
              :class="partnersVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
              class="h-12 flex items-center justify-center bg-white border rounded"
            >
              <img
                :src="partner.logo_url || 'https://placehold.co/120x30?text=Logo'"
                :alt="partner.name || 'Partner'"
                class="max-h-8"
              />
            </div>
          </template>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section
      ref="testimonialsRef"
      :class="testimonialsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-16"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6">What our users say</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="(testimonial, index) in testimonials"
            :key="index"
            :style="{ animationDelay: `${index * 120}ms` }"
            :class="testimonialsVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="p-6 bg-white border rounded-2xl"
          >
            <p class="text-sm text-gray-700">"{{ testimonial.text }}"</p>
            <div class="mt-4 text-sm text-gray-500">— {{ testimonial.author }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section
      ref="howItWorksRef"
      :class="howItWorksVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-16 bg-gray-50"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6">How FinCompare Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="(step, index) in howItWorks"
            :key="index"
            :style="{ animationDelay: `${index * 120}ms` }"
            :class="howItWorksVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="p-6 bg-white border rounded-lg text-center"
          >
            <div class="mx-auto h-12 w-12 rounded-full flex items-center justify-center mb-3 bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] ring-1 ring-[color:var(--brand-primary)]/20">
              {{ step.number }}
            </div>
            <h3 class="font-semibold">{{ step.title }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section
      ref="faqRef"
      :class="faqVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-16"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6">Frequently asked questions</h2>
        <div class="bg-white border rounded-2xl divide-y">
          <div
            v-for="(faq, index) in homeFaqs"
            :key="index"
            class="p-5"
          >
            <button
              @click="toggleFaq(index)"
              class="w-full flex items-center justify-between text-left"
            >
              <span class="font-medium text-gray-900">{{ faq.question }}</span>
              <svg
                :class="openFaqs[index] ? 'rotate-180' : ''"
                class="h-5 w-5 text-gray-500 transition"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div
              v-show="openFaqs[index]"
              v-cloak
              class="mt-2 text-sm text-gray-600"
            >
              {{ faq.answer }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Band -->
    <section
      ref="ctaRef"
      :class="ctaVisible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
      class="py-12"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white px-6 py-8 flex flex-col md:flex-row items-center justify-between">
          <div class="mb-4 md:mb-0">
            <h3 class="text-xl font-bold">Not sure which product fits?</h3>
            <p class="text-white/90 mt-1">Tell us your needs — we'll help you choose.</p>
          </div>
          <router-link
            to="/products"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-[color:var(--brand-primary)] font-semibold shadow hover:bg-white/90"
          >
            Get Started
          </router-link>
        </div>
      </div>
    </section>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useReveal, useHomeData, useSEO } from '../composables';
import { getImageUrl } from '../utils';
import GuestLayout from '../layouts/GuestLayout.vue';
import ProductCard from '../components/ProductCard.vue';
import { EyeIcon, LightbulbIcon, CheckCircleIcon, SearchIcon, DollarIcon } from '../components/icons';

const router = useRouter();
const searchQuery = ref('');

const { featuredProducts, categories, partners, loading, fetchHomeData } = useHomeData();

// Filter out any invalid partners
const validPartners = computed(() => {
  return (partners.value || []).filter(p => p && (p.id || p.name));
});

// Stats data
const stats = ref([
  { target: 250000, mode: 'kplus', label: 'Comparisons made', display: '0' },
  { target: 1200, mode: 'plus', label: 'Products listed', display: '0' },
  { target: 95, mode: 'percent', label: 'User satisfaction', display: '0%' },
  { target: 50, mode: 'plus', label: 'Trusted partners', display: '0' }
]);

// Benefits data
const benefits = ref([
  {
    title: 'Transparent comparisons',
    description: 'Clear specs and side-by-side views so you always know what you get.',
    icon: EyeIcon
  },
  {
    title: 'Guidance, not noise',
    description: 'Smart defaults and highlights to help you decide faster.',
    icon: LightbulbIcon
  },
  {
    title: 'No hidden costs',
    description: 'We surface fees and terms upfront — no surprises.',
    icon: CheckCircleIcon
  }
]);

// Testimonials
const testimonials = ref([
  { text: 'Found a perfect card in minutes. The comparison made it obvious.', author: 'Alex P.' },
  { text: 'Loved the clarity. Sent an inquiry and got a reply same day.', author: 'Priya S.' },
  { text: 'Side-by-side features saved me a ton of time.', author: 'Michael R.' }
]);

// How it works
const howItWorks = ref([
  { number: 1, title: 'Browse & Filter', description: 'Explore categories and filter by what matters to you.' },
  { number: 2, title: 'Compare', description: 'Select products and compare them side-by-side.' },
  { number: 3, title: 'Apply & Track', description: 'Send an inquiry or apply — we route you to partners.' }
]);

// FAQ
const homeFaqs = ref([
  {
    question: 'Does comparing affect my credit score?',
    answer: 'No. Viewing and comparing products on FinCompare does not impact your credit score.'
  },
  {
    question: 'How do you make recommendations?',
    answer: 'We use product data and your filters to surface options that align with your needs.'
  },
  {
    question: 'Can I apply directly through FinCompare?',
    answer: 'Yes — use "Send Inquiry" and we route you to the right partner or form.'
  }
]);

const openFaqs = ref({});

// Reveal animations
const { visible: statsVisible, elementRef: statsRef, init: initStats } = useReveal(0.4);
const { visible: categoriesVisible, elementRef: categoriesRef, init: initCategories } = useReveal(0.18);
const { visible: benefitsVisible, elementRef: benefitsRef, init: initBenefits } = useReveal(0.18);
const { visible: productsVisible, elementRef: productsRef, init: initProducts } = useReveal(0.18);
const { visible: partnersVisible, elementRef: partnersRef, init: initPartners } = useReveal(0.18);
const { visible: testimonialsVisible, elementRef: testimonialsRef, init: initTestimonials } = useReveal(0.18);
const { visible: howItWorksVisible, elementRef: howItWorksRef, init: initHowItWorks } = useReveal(0.18);
const { visible: faqVisible, elementRef: faqRef, init: initFaq } = useReveal(0.18);
const { visible: ctaVisible, elementRef: ctaRef, init: initCta } = useReveal(0.18);

// SEO setup
useSEO({
  title: 'Home',
  description: 'Find and compare the best financial products including loans, credit cards, and more. Compare side-by-side and apply with confidence.',
  keywords: ['financial products', 'compare loans', 'credit cards', 'personal loans', 'financial comparison']
});

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({ path: '/products', query: { q: searchQuery.value.trim() } });
  } else {
    // If search is empty, just go to products page
    router.push({ path: '/products' });
  }
};

const handleFilterClick = (filter) => {
  // Navigate to products page with the filter as search query
  router.push({ path: '/products', query: { q: filter } });
};

const toggleFaq = (index) => {
  openFaqs.value[index] = !openFaqs.value[index];
};

// Track if animation has been triggered to prevent multiple runs
const statsAnimated = ref(false);

// Animate stats counters
const animateStats = () => {
  // Prevent multiple animations
  if (statsAnimated.value) return;

  // Ensure stats are visible before animating
  if (!statsVisible.value) {
    // If not visible yet, wait a bit and try again
    setTimeout(() => {
      if (statsVisible.value && !statsAnimated.value) {
        animateStats();
      }
    }, 100);
    return;
  }

  statsAnimated.value = true;

  stats.value.forEach((stat, index) => {
    const duration = 1400;
    const startTs = performance.now();
    const startVal = 0;
    const easeOut = (t) => 1 - Math.pow(1 - t, 3);

    const step = (now) => {
      const p = Math.min((now - startTs) / duration, 1);
      const val = startVal + (stat.target - startVal) * easeOut(p);

      if (stat.mode === 'percent') {
        stat.display = Math.round(val) + '%';
      } else if (stat.mode === 'kplus') {
        if (val >= 100000) {
          stat.display = Math.round(val / 1000) + 'k+';
        } else {
          stat.display = Math.round(val).toLocaleString() + '+';
        }
      } else if (stat.mode === 'plus') {
        stat.display = Math.round(val).toLocaleString() + '+';
      } else {
        stat.display = Math.round(val).toLocaleString();
      }

      if (p < 1) {
        requestAnimationFrame(step);
      } else {
        // Ensure final value is set correctly
        if (stat.mode === 'percent') {
          stat.display = stat.target + '%';
        } else if (stat.mode === 'kplus') {
          if (stat.target >= 100000) {
            stat.display = Math.round(stat.target / 1000) + 'k+';
          } else {
            stat.display = Math.round(stat.target).toLocaleString() + '+';
          }
        } else if (stat.mode === 'plus') {
          stat.display = Math.round(stat.target).toLocaleString() + '+';
        } else {
          stat.display = Math.round(stat.target).toLocaleString();
        }
      }
    };

    setTimeout(() => requestAnimationFrame(step), index * 100);
  });
};

// Watch for stats visibility changes
watch(statsVisible, (isVisible) => {
  if (isVisible && !statsAnimated.value) {
    // Small delay to ensure DOM is ready
    setTimeout(() => {
      animateStats();
    }, 50);
  }
});

onMounted(async () => {
  await fetchHomeData();

  // Initialize reveal animations
  initStats();
  initCategories();
  initBenefits();
  initProducts();
  initPartners();
  initTestimonials();
  initHowItWorks();
  initFaq();
  initCta();

  // Check if stats section is already visible (e.g., on initial load)
  // This handles the case where the element is visible before the observer triggers
  if (statsRef.value) {
    const rect = statsRef.value.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
    if (isVisible && !statsAnimated.value) {
      // If already visible, trigger animation after a short delay
      setTimeout(() => {
        if (statsVisible.value && !statsAnimated.value) {
          animateStats();
        }
      }, 200);
    }
  }
});
</script>

<style scoped>
[v-cloak] {
  display: none;
}
</style>
