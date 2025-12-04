<template>
  <GuestLayout>
    <HeroSearch
      :filter-pills="filterPills"
      @filter-click="handleFilterClick"
    />

    <StatsSection
      ref="statsRef"
      :stats="stats"
      :visible="statsVisible"
      :threshold="0.4"
    />

    <CategoriesSection
      ref="categoriesRef"
      :categories="categories"
      :visible="categoriesVisible"
    />

    <BenefitsSection
      ref="benefitsRef"
      :benefits="benefits"
      :visible="benefitsVisible"
    />

    <FeaturedProductsSection
      ref="productsRef"
      :products="featuredProducts"
      :loading="loading"
      :visible="productsVisible"
    />

    <PartnersSection
      ref="partnersRef"
      :partners="validPartners"
      :loading="loading"
      :visible="partnersVisible"
    />

    <TestimonialsSection
      ref="testimonialsRef"
      :testimonials="testimonials"
      :visible="testimonialsVisible"
    />

    <HowItWorksSection
      ref="howItWorksRef"
      :steps="howItWorks"
      :visible="howItWorksVisible"
    />

    <FAQSection
      ref="faqRef"
      :faqs="homeFaqs"
      :visible="faqVisible"
    />

    <CTASection
      ref="ctaRef"
      :visible="ctaVisible"
    />
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { useReveal, useHomeData, useSEO } from '../composables';
import GuestLayout from '../layouts/GuestLayout.vue';
import {
  HeroSearch,
  StatsSection,
  CategoriesSection,
  BenefitsSection,
  FeaturedProductsSection,
  PartnersSection,
  TestimonialsSection,
  HowItWorksSection,
  FAQSection,
  CTASection
} from '../components/home';
import { EyeIcon, LightbulbIcon, CheckCircleIcon } from '../components/icons';

const router = useRouter();
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

// Filter pills for hero search
const filterPills = ref([
  { label: '0% APR', value: '0% APR' },
  { label: 'Cashback', value: 'cashback' },
  { label: 'Travel', value: 'travel' },
  { label: 'Personal Loans', value: 'personal loan' }
]);

// Component refs for reveal animations
const statsRef = ref(null);
const categoriesRef = ref(null);
const benefitsRef = ref(null);
const productsRef = ref(null);
const partnersRef = ref(null);
const testimonialsRef = ref(null);
const howItWorksRef = ref(null);
const faqRef = ref(null);
const ctaRef = ref(null);

// Reveal animations
const { visible: statsVisible, init: initStats } = useReveal(0.4);
const { visible: categoriesVisible, init: initCategories } = useReveal(0.18);
const { visible: benefitsVisible, init: initBenefits } = useReveal(0.18);
const { visible: productsVisible, init: initProducts } = useReveal(0.18);
const { visible: partnersVisible, init: initPartners } = useReveal(0.18);
const { visible: testimonialsVisible, init: initTestimonials } = useReveal(0.18);
const { visible: howItWorksVisible, init: initHowItWorks } = useReveal(0.18);
const { visible: faqVisible, init: initFaq } = useReveal(0.18);
const { visible: ctaVisible, init: initCta } = useReveal(0.18);

// SEO setup
useSEO({
  title: 'Home',
  description: 'Find and compare the best financial products including loans, credit cards, and more. Compare side-by-side and apply with confidence.',
  keywords: ['financial products', 'compare loans', 'credit cards', 'personal loans', 'financial comparison']
});

const handleFilterClick = (filter) => {
  router.push({ path: '/products', query: { q: filter } });
};

onMounted(async () => {
  await fetchHomeData();

  // Initialize reveal animations - wait for next tick to ensure refs are available
  await nextTick();

  if (statsRef.value?.$el) {
    initStats(statsRef.value.$el);
  }
  if (categoriesRef.value?.$el) {
    initCategories(categoriesRef.value.$el);
  }
  if (benefitsRef.value?.$el) {
    initBenefits(benefitsRef.value.$el);
  }
  if (productsRef.value?.$el) {
    initProducts(productsRef.value.$el);
  }
  if (partnersRef.value?.$el) {
    initPartners(partnersRef.value.$el);
  }
  if (testimonialsRef.value?.$el) {
    initTestimonials(testimonialsRef.value.$el);
  }
  if (howItWorksRef.value?.$el) {
    initHowItWorks(howItWorksRef.value.$el);
  }
  if (faqRef.value?.$el) {
    initFaq(faqRef.value.$el);
  }
  if (ctaRef.value?.$el) {
    initCta(ctaRef.value.$el);
  }
});
</script>

