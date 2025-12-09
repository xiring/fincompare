<template>
  <GuestLayout>
    <div v-if="product" class="w-full">
      <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
          <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
          <nav class="text-sm mb-6 inline-flex items-center px-4 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 shadow-sm">
            <router-link to="/" class="text-white/90 hover:text-white hover:underline font-medium transition-colors">{{ TEXT.HOME }}</router-link>
            <span class="mx-2 text-white/60">/</span>
            <router-link to="/products" class="text-white/90 hover:text-white hover:underline font-medium transition-colors">{{ TEXT.PRODUCTS }}</router-link>
            <span class="mx-2 text-white/60">/</span>
            <span class="text-white font-semibold break-words">{{ product.name }}</span>
          </nav>
          <div class="flex flex-col lg:grid lg:grid-cols-12 items-start gap-6 lg:gap-8">
            <div class="flex-shrink-0 lg:col-span-2">
              <img
                v-if="product.image_url || product.image"
                :src="productImageUrl || undefined"
                :alt="product.name"
                loading="lazy"
                class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white/10 object-cover ring-4 ring-white/30 shadow-2xl"
              />
              <img
                v-else-if="product.partner?.logo_url"
                :src="partnerLogoUrl"
                :alt="product.partner?.name"
                loading="lazy"
                class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl bg-white/10 object-contain ring-2 ring-white/30 shadow-xl p-2"
              />
            </div>
            <div class="flex-1 min-w-0 w-full lg:col-span-7">
              <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight break-words">{{ product.name }}</h1>
              <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                <div class="flex items-center gap-2.5">
                  <svg class="w-4 h-4 text-white/80 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                  <span class="text-white/90 font-medium">{{ product.partner?.name || 'Partner' }}</span>
                </div>
                <span v-if="product.is_featured" class="px-3 py-1 text-xs rounded-full bg-amber-300 text-slate-900 font-bold shadow-sm whitespace-nowrap">{{ TEXT.FEATURED }}</span>
                <span class="px-3 py-1 text-xs rounded-full bg-white/20 backdrop-blur-sm text-white font-medium whitespace-nowrap">{{ product.status || 'active' }}</span>
              </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto lg:col-span-3 lg:justify-end">
              <button
                @click="toggleCompare"
                type="button"
                :class="inCompare
                  ? 'bg-white/20 text-white border-2 border-white/40 hover:bg-white/30 backdrop-blur-sm'
                  : 'bg-white border-2 border-white/30 hover:bg-white/95 text-[color:var(--brand-primary)] shadow-lg hover:shadow-xl'"
                class="inline-flex items-center justify-center px-5 py-3 rounded-xl font-semibold transition-all duration-200 text-sm sm:text-base whitespace-nowrap"
              >
                {{ inCompare ? TEXT.IN_COMPARE : TEXT.ADD_TO_COMPARE }}
              </button>
              <router-link
                :to="`/lead?product=${product.slug || product.id}`"
                class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white text-[color:var(--brand-primary)] font-semibold shadow-lg hover:shadow-xl hover:bg-white/95 transition-all duration-200 text-sm sm:text-base whitespace-nowrap"
              >
                {{ TEXT.SEND_INQUIRY }}
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-32">
        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8 animate-fade-in-up">
          <div class="group p-5 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 hover:border-[color:var(--brand-primary)]/30">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[color:var(--brand-primary)]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[color:var(--brand-primary)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ TEXT.INTEREST_RATE }}</div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ product.attribute_highlights?.interest_rate || '—' }}</div>
          </div>
          <div class="group p-5 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 hover:border-[color:var(--brand-primary)]/30">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[color:var(--brand-primary)]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[color:var(--brand-primary)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ TEXT.MAX_AMOUNT }}</div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ product.attribute_highlights?.max_amount || '—' }}</div>
          </div>
          <div class="group p-5 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 hover:border-[color:var(--brand-primary)]/30">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[color:var(--brand-primary)]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[color:var(--brand-primary)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ TEXT.LABEL_PARTNER }}</div>
            </div>
            <div class="text-xl font-bold text-gray-900 truncate">{{ product.partner?.name || '—' }}</div>
          </div>
        </div>

        <div class="mt-8 animate-fade-in-up">
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50/50">
              <nav class="flex space-x-1 px-4" aria-label="Tabs">
                <button
                  v-for="tab in tabs"
                  :key="tab.key"
                  @click="activeTab = tab.key"
                  :class="activeTab === tab.key
                    ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)] bg-white'
                    : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100/50'"
                  class="whitespace-nowrap border-b-2 px-5 py-4 text-sm font-semibold transition-all duration-200"
                >
                  {{ tab.label }}
                </button>
              </nav>
            </div>

            <div v-show="activeTab === 'overview'" class="p-6 prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)] prose-a:no-underline hover:prose-a:underline">
              <div v-if="product.description" v-html="product.description"></div>
              <p v-else class="text-gray-600">{{ TEXT.NO_DESCRIPTION }}</p>
            </div>

            <div v-show="activeTab === 'features'" class="p-6">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h2 class="text-lg font-semibold text-gray-900">{{ TEXT.TAB_FEATURES_ATTRIBUTES }}</h2>
                <div class="relative">
                  <input
                    v-model="featureQuery"
                    type="text"
                    :placeholder="TEXT.LABEL_SEARCH_ATTRIBUTES"
                  class="w-full sm:w-64 rounded-lg border-gray-300 text-sm focus:border-[color:var(--brand-primary)] focus:ring-2 focus:ring-[color:var(--brand-primary)]/20 pl-10 pr-3 py-2.5 transition-all"
                >
                <SearchIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                </div>
              </div>
              <div v-if="filteredAttributes.length > 0" class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="min-w-full divide-y divide-gray-200">
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="(attr, index) in filteredAttributes"
                      :key="attr.name"
                      :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'"
                      class="hover:bg-[color:var(--brand-primary)]/5 transition-colors"
                    >
                      <td class="px-5 py-4 text-sm font-semibold text-gray-900 w-1/3">{{ attr.name }}</td>
                      <td class="px-5 py-4 text-sm text-gray-700">{{ attr.value || '—' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="border border-gray-200 rounded-xl p-12 text-center bg-gray-50/50">
                <p class="text-gray-600">{{ TEXT.NO_ATTRIBUTES }}</p>
              </div>
            </div>

            <div v-show="activeTab === 'eligibility'" class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-5">{{ TEXT.ELIGIBILITY_CRITERIA }}</h3>
              <div class="prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)]">
                <div v-if="product.eligibility" v-html="product.eligibility"></div>
                <p v-else class="text-gray-600">{{ TEXT.DETAILS_BY_PARTNER }}</p>
              </div>
            </div>

            <div v-show="activeTab === 'documents'" class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-5">{{ TEXT.REQUIRED_DOCUMENTS }}</h3>
              <div class="prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)]">
                <div v-if="product.documents" v-html="product.documents"></div>
                <p v-else class="text-gray-600">{{ TEXT.DETAILS_BY_PARTNER }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-10 flex flex-wrap items-center gap-3 pb-4">
          <button
            @click="copyLink"
            type="button"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-all duration-200 shadow-sm hover:shadow-md"
          >
            <CopyIcon className="w-4 h-4 mr-2.5" />
            {{ TEXT.COPY_LINK }}
          </button>
          <button
            @click="toggleCompare"
            type="button"
            :class="inCompare
              ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] shadow-sm hover:shadow-md'
              : 'bg-white border-2 border-[color:var(--brand-primary)] text-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)]/5'"
            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-semibold transition-all duration-200"
          >
            {{ inCompare ? TEXT.REMOVE_FROM_COMPARE : TEXT.ADD_TO_COMPARE }}
          </button>
          <router-link
            :to="`/lead?product=${product.slug || product.id}`"
            class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-white transition-all duration-200 shadow-md hover:shadow-lg btn-brand-primary"
          >
            {{ TEXT.APPLY_NOW }}
          </router-link>
          <router-link
            v-if="inCompare"
            to="/compare"
            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-semibold text-white transition-all duration-200 shadow-md hover:shadow-lg btn-brand-primary"
          >
            {{ TEXT.COMPARE_NOW }}
          </router-link>
        </div>
      </div>

      <!-- Fixed bottom bar -->
      <div class="fixed bottom-0 left-0 right-0 z-30 bg-white/98 backdrop-blur-md border-t border-gray-200 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
          <div class="flex items-center gap-3 min-w-0 flex-1">
              <img
                v-if="productImageUrl"
                :src="productImageUrl"
                :alt="product.name"
              loading="lazy"
              class="w-12 h-12 rounded-xl bg-gray-100 object-cover border border-gray-200 shadow-sm"
            />
            <img
              v-else-if="product.partner?.logo_url"
              :src="partnerLogoUrl"
              loading="lazy"
              :alt="product.partner?.name"
              class="w-12 h-12 rounded-xl bg-gray-100 object-contain border border-gray-200 shadow-sm"
            />
            <div class="min-w-0 flex-1">
              <div class="text-sm font-semibold text-gray-900 truncate">{{ product.name }}</div>
              <div class="text-xs text-gray-500 truncate">{{ product.partner?.name }}</div>
            </div>
          </div>
          <div class="flex items-center gap-3 flex-shrink-0">
            <button
              @click="toggleCompare"
              type="button"
              :class="inCompare
                ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] shadow-sm hover:shadow-md'
                : 'bg-white border-2 border-[color:var(--brand-primary)] text-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)]/5'"
              class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 whitespace-nowrap"
            >
              {{ inCompare ? TEXT.REMOVE_FROM_COMPARE : TEXT.ADD_TO_COMPARE }}
            </button>
            <router-link
              :to="`/lead?product=${product.slug || product.id}`"
              class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 shadow-md hover:shadow-lg whitespace-nowrap btn-brand-primary"
            >
              {{ TEXT.APPLY_NOW }}
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error && !loading" class="w-full">
      <HeroSection :title="TEXT.PRODUCT_NOT_FOUND" />
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <ErrorState
          :title="ERROR_MESSAGES.PRODUCT.LOAD"
          :message="error"
          back-url="/products"
          :back-text="TEXT.BROWSE_PRODUCTS"
          @retry="retryLoad"
        />
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-else-if="loading" class="w-full">
      <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
          <div class="animate-pulse">
            <div class="h-4 bg-white/20 rounded w-48 mb-4"></div>
            <div class="flex flex-col sm:flex-row items-start gap-4">
              <div class="w-24 h-24 bg-white/20 rounded-lg"></div>
              <div class="flex-1 space-y-3">
                <div class="h-8 bg-white/20 rounded w-3/4"></div>
                <div class="h-4 bg-white/20 rounded w-1/2"></div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
          <div v-for="i in 3" :key="i" class="p-4 rounded-2xl bg-white border animate-pulse">
            <div class="h-4 bg-gray-200 rounded w-24 mb-2"></div>
            <div class="h-6 bg-gray-200 rounded w-32"></div>
          </div>
        </div>
        <div class="space-y-4">
          <div class="h-10 bg-gray-200 rounded w-64 animate-pulse"></div>
          <div class="h-64 bg-gray-100 rounded-lg animate-pulse"></div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { apiService } from '../../services/api';
import { useCompare, useSEO, useImageUrl, useErrorHandling } from '../../composables';
import { getExcerpt, copyToClipboard, TEXT, ERROR_MESSAGES, SUCCESS_MESSAGES } from '../../utils';
import { useToastStore } from '../../stores/toast';
import { SearchIcon, CopyIcon } from '../../components/icons';
import { ErrorState, HeroSection } from '../../components';
// @ts-ignore - Vue component shim should handle this
import GuestLayout from '../../layouts/GuestLayout.vue';
import type { Product } from '../../types/index';

interface ProductAttribute {
  id: number;
  name: string;
  value?: string | number | boolean;
  [key: string]: any;
}

const route = useRoute();
const product = ref<Product | null>(null);
const attributes = ref<ProductAttribute[]>([]);
const loading = ref<boolean>(true);
const activeTab = ref<'overview' | 'features' | 'eligibility' | 'documents' | string>('overview');
const featureQuery = ref<string>('');

const { toggleCompare: toggleCompareAction, isInCompare: checkInCompare } = useCompare();
const { error, handleError, clearError } = useErrorHandling();
const { productImageUrl, partnerLogoUrl } = useImageUrl(product);
const toastStore = useToastStore();

const inCompare = computed<boolean>(() => (product.value ? checkInCompare(product.value.id) : false));

const tabs = [
  { key: 'overview', label: TEXT.TAB_OVERVIEW },
  { key: 'features', label: TEXT.TAB_FEATURES },
  { key: 'eligibility', label: TEXT.TAB_ELIGIBILITY },
  { key: 'documents', label: TEXT.TAB_DOCUMENTS },
];

const filteredAttributes = computed<ProductAttribute[]>(() => {
  if (!featureQuery.value) return attributes.value;
  const query = featureQuery.value.toLowerCase();
  return attributes.value.filter(
    (attr: ProductAttribute) =>
      (attr.name || '').toLowerCase().includes(query) || (String(attr.value || '')).toLowerCase().includes(query)
  );
});

const toggleCompare = (): void => {
  if (product.value) {
    toggleCompareAction(product.value.id);
  }
};

const copyLink = async (): Promise<void> => {
  const success = await copyToClipboard(window.location.href);
  if (success) {
    toastStore.success(SUCCESS_MESSAGES.COPY_LINK);
  } else {
    toastStore.error(ERROR_MESSAGES.COPY_LINK);
  }
};

const retryLoad = async (): Promise<void> => {
  clearError();
  loading.value = true;
  await loadProduct();
};

// SEO setup - will be updated when product loads
const getProductDescription = (): string => {
  if (!product.value) return '';
  return getExcerpt(product.value.description || '', 160);
};

const getProductKeywords = (): string[] => {
  if (!product.value) return [];
  const keywords: string[] = [product.value.name];
  if (product.value.partner?.name) keywords.push(product.value.partner.name);
  if ((product.value as any).product_category?.name) keywords.push((product.value as any).product_category.name);
  return keywords;
};

// Initialize SEO with default values
useSEO({
  title: 'Product Details',
  description: 'View product details and compare financial products',
  type: 'product',
});

// Update SEO when product loads
watch(
  product,
  (newProduct) => {
    if (newProduct) {
      useSEO({
        title: newProduct.name || 'Product Details',
        description: getProductDescription() || `Learn more about ${newProduct.name} and compare with other financial products.`,
        image: newProduct.image_url || newProduct.partner?.logo_url,
        keywords: getProductKeywords(),
        type: 'product',
      });
    }
  },
  { immediate: true }
);

const loadProduct = async (): Promise<void> => {
  const slug = route.params.slug as string;

  try {
    const response = await apiService.getProduct(slug);
    // Handle both response formats: { data: Product } or Product
    const productData = (response.data as any).product || response.data;
    product.value = productData as Product;
    attributes.value = (response.data as any).attributes || [];
    clearError();
  } catch (err: any) {
    handleError(err, ERROR_MESSAGES.PRODUCT.LOAD_DETAIL);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadProduct();
});
</script>

<style scoped>
.prose :deep(h1),
.prose :deep(h2),
.prose :deep(h3) {
  @apply font-semibold text-gray-900 mt-6 mb-4;
}

.prose :deep(p) {
  @apply mb-4;
}

.prose :deep(a) {
  @apply text-[color:var(--brand-primary)] no-underline hover:underline;
}
</style>
