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

<script setup lang="ts">
import { ref, onMounted, computed, nextTick, markRaw, type ComponentPublicInstance } from 'vue';
import { useRouter } from 'vue-router';
import { useReveal, useHomeData, useSEO } from '../composables';
import { TEXT } from '../utils';
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
import type { Partner } from '../../types/index';

const router = useRouter();
const { featuredProducts, categories, partners, loading, fetchHomeData } = useHomeData();

// Filter out any invalid partners
const validPartners = computed(() => {
  return (partners.value || []).filter((p: Partner) => p && (p.id || p.name));
});

interface Stat {
  target: number;
  mode: 'kplus' | 'plus' | 'percent';
  label: string;
  display: string;
}

// Stats data
const stats = ref<Stat[]>([
  { target: 250000, mode: 'kplus', label: TEXT.STATS_COMPARISONS_MADE, display: '0' },
  { target: 1200, mode: 'plus', label: TEXT.STATS_PRODUCTS_LISTED, display: '0' },
  { target: 95, mode: 'percent', label: TEXT.STATS_USER_SATISFACTION, display: '0%' },
  { target: 50, mode: 'plus', label: TEXT.STATS_TRUSTED_PARTNERS, display: '0' },
]);

interface Benefit {
  title: string;
  description: string;
  icon: any;
}

// Benefits data
const benefits = ref<Benefit[]>([
  {
    title: TEXT.BENEFIT_TRANSPARENT_TITLE,
    description: TEXT.BENEFIT_TRANSPARENT_DESC,
    icon: markRaw(EyeIcon),
  },
  {
    title: TEXT.BENEFIT_GUIDANCE_TITLE,
    description: TEXT.BENEFIT_GUIDANCE_DESC,
    icon: markRaw(LightbulbIcon),
  },
  {
    title: TEXT.BENEFIT_NO_HIDDEN_TITLE,
    description: TEXT.BENEFIT_NO_HIDDEN_DESC,
    icon: markRaw(CheckCircleIcon),
  },
]);

interface Testimonial {
  text: string;
  author: string;
}

// Testimonials
const testimonials = ref<Testimonial[]>([
  { text: TEXT.TESTIMONIAL_1_TEXT, author: TEXT.TESTIMONIAL_1_AUTHOR },
  { text: TEXT.TESTIMONIAL_2_TEXT, author: TEXT.TESTIMONIAL_2_AUTHOR },
  { text: TEXT.TESTIMONIAL_3_TEXT, author: TEXT.TESTIMONIAL_3_AUTHOR },
]);

interface HowItWorksStep {
  number: number;
  title: string;
  description: string;
}

// How it works
const howItWorks = ref<HowItWorksStep[]>([
  { number: 1, title: TEXT.HOW_IT_WORKS_BROWSE_TITLE, description: TEXT.HOW_IT_WORKS_BROWSE_DESC },
  { number: 2, title: TEXT.HOW_IT_WORKS_COMPARE_TITLE, description: TEXT.HOW_IT_WORKS_COMPARE_DESC },
  { number: 3, title: TEXT.HOW_IT_WORKS_APPLY_TITLE, description: TEXT.HOW_IT_WORKS_APPLY_DESC },
]);

interface HomeFaq {
  question: string;
  answer: string;
}

// FAQ
const homeFaqs = ref<HomeFaq[]>([
  {
    question: TEXT.FAQ_CREDIT_SCORE_Q,
    answer: TEXT.FAQ_CREDIT_SCORE_A,
  },
  {
    question: TEXT.FAQ_RECOMMENDATIONS_Q,
    answer: TEXT.FAQ_RECOMMENDATIONS_A,
  },
  {
    question: TEXT.FAQ_APPLY_DIRECTLY_Q,
    answer: TEXT.FAQ_APPLY_DIRECTLY_A,
  },
]);

interface FilterPill {
  label: string;
  value: string;
}

// Filter pills for hero search
const filterPills = ref<FilterPill[]>([
  { label: TEXT.FILTER_0_APR, value: TEXT.FILTER_0_APR },
  { label: TEXT.FILTER_CASHBACK, value: 'cashback' },
  { label: TEXT.FILTER_TRAVEL, value: 'travel' },
  { label: TEXT.FILTER_PERSONAL_LOANS, value: 'personal loan' },
]);

// Component refs for reveal animations
const statsRef = ref<ComponentPublicInstance | null>(null);
const categoriesRef = ref<ComponentPublicInstance | null>(null);
const benefitsRef = ref<ComponentPublicInstance | null>(null);
const productsRef = ref<ComponentPublicInstance | null>(null);
const partnersRef = ref<ComponentPublicInstance | null>(null);
const testimonialsRef = ref<ComponentPublicInstance | null>(null);
const howItWorksRef = ref<ComponentPublicInstance | null>(null);
const faqRef = ref<ComponentPublicInstance | null>(null);
const ctaRef = ref<ComponentPublicInstance | null>(null);

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
  title: TEXT.HOME,
  description: TEXT.SEO_HOME_DESCRIPTION,
  keywords: TEXT.SEO_KEYWORDS_FINANCIAL_PRODUCTS,
});

const handleFilterClick = (filter: string): void => {
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

