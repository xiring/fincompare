<template>
  <GuestLayout>
    <div v-if="product" class="w-full">
      <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
          <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
          <nav class="text-sm mb-4 inline-flex items-center px-3 py-1.5 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20">
            <router-link to="/" class="text-white hover:underline font-medium">Home</router-link>
            <span class="mx-2 text-white/80">/</span>
            <router-link to="/products" class="text-white hover:underline font-medium">Products</router-link>
            <span class="mx-2 text-white/80">/</span>
            <span class="text-white font-semibold">{{ product.name }}</span>
          </nav>
          <div class="flex flex-col sm:flex-row items-start gap-4">
            <img
              v-if="product.image_url"
              :src="productImageUrl"
              :alt="product.name"
              class="w-24 h-24 rounded-lg bg-white/10 object-cover ring-2 ring-white/20 shadow-lg"
            />
            <img
              v-else-if="product.partner?.logo_url"
              :src="partnerLogoUrl"
              :alt="product.partner?.name"
              class="w-16 h-16 rounded bg-white/10 object-contain ring-1 ring-white/20"
            />
            <div class="flex-1">
              <h1 class="text-3xl font-extrabold tracking-tight">{{ product.name }}</h1>
              <div class="mt-1 flex items-center gap-3 text-sm text-white/90">
                <span>{{ product.partner?.name || 'Partner' }}</span>
                <span v-if="product.is_featured" class="px-2 py-0.5 text-xs rounded-full bg-amber-300 text-slate-900 font-semibold">Featured</span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-white/20">{{ product.status || 'active' }}</span>
              </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 mt-4 sm:mt-0">
              <button
                @click="toggleCompare"
                type="button"
                :class="inCompare ? 'bg-white/20 text-white border border-white/30 hover:bg-white/30' : 'bg-white border border-white/20 hover:bg-white/90'"
                class="inline-flex items-center justify-center px-4 py-2.5 sm:py-3 rounded-xl font-semibold transition-colors text-sm sm:text-base"
                :style="inCompare ? '' : 'color: var(--brand-primary);'"
              >
                {{ inCompare ? 'In Compare' : 'Add to Compare' }}
              </button>
              <router-link
                :to="`/lead?product=${product.slug || product.id}`"
                class="inline-flex items-center justify-center px-5 py-2.5 sm:py-3 rounded-xl bg-white font-semibold shadow hover:bg-white/90 transition-colors text-sm sm:text-base"
                style="color: var(--brand-primary);"
              >
                Send Inquiry
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 animate-fade-in-up">
          <div class="p-4 rounded-2xl bg-white border">
            <div class="text-xs text-gray-500">Interest Rate</div>
            <div class="text-lg font-semibold text-gray-900">{{ product.attribute_highlights?.interest_rate || '—' }}</div>
          </div>
          <div class="p-4 rounded-2xl bg-white border">
            <div class="text-xs text-gray-500">Max Amount</div>
            <div class="text-lg font-semibold text-gray-900">{{ product.attribute_highlights?.max_amount || '—' }}</div>
          </div>
          <div class="p-4 rounded-2xl bg-white border">
            <div class="text-xs text-gray-500">Partner</div>
            <div class="text-lg font-semibold text-gray-900">{{ product.partner?.name || '—' }}</div>
          </div>
        </div>

        <div class="mt-6 animate-fade-in-up">
          <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="-mb-px flex space-x-6 min-w-max" aria-label="Tabs">
              <button
                v-for="tab in tabs"
                :key="tab.key"
                @click="activeTab = tab.key"
                :class="activeTab === tab.key ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition-colors"
              >
                {{ tab.label }}
              </button>
            </nav>
          </div>

          <div v-show="activeTab === 'overview'" class="mt-6 prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)] prose-a:no-underline hover:prose-a:underline">
            <div v-if="product.description" v-html="product.description"></div>
            <p v-else class="text-gray-600">No description available for this product.</p>
          </div>

          <div v-show="activeTab === 'features'" class="mt-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
              <h2 class="text-lg font-semibold">Features & Attributes</h2>
              <div class="relative">
                <input
                  v-model="featureQuery"
                  type="text"
                  placeholder="Search attributes..."
                  class="w-full sm:w-64 rounded-lg border-gray-300 text-sm focus-brand pl-9 pr-3 py-2"
                >
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            <div v-if="filteredAttributes.length > 0" class="overflow-x-auto bg-white border rounded-lg">
              <table class="min-w-full divide-y">
                <tbody class="divide-y">
                  <tr
                    v-for="attr in filteredAttributes"
                    :key="attr.name"
                    class="hover:bg-gray-50 transition-colors"
                  >
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 w-1/3">{{ attr.name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ attr.value || '—' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="bg-white border rounded-lg p-8 text-center">
              <p class="text-gray-600">No attributes available for this product.</p>
            </div>
          </div>

          <div v-show="activeTab === 'eligibility'" class="mt-8">
            <div class="bg-white border rounded-lg p-6">
              <h3 class="text-lg font-semibold mb-4">Eligibility Criteria</h3>
              <div class="prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)]">
                <div v-if="product.eligibility" v-html="product.eligibility"></div>
                <p v-else class="text-gray-600">Details provided by the partner.</p>
              </div>
            </div>
          </div>

          <div v-show="activeTab === 'documents'" class="mt-8">
            <div class="bg-white border rounded-lg p-6">
              <h3 class="text-lg font-semibold mb-4">Required Documents</h3>
              <div class="prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)]">
                <div v-if="product.documents" v-html="product.documents"></div>
                <p v-else class="text-gray-600">Details provided by the partner.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-10 flex flex-wrap items-center gap-3 pb-4">
          <button
            @click="copyLink"
            type="button"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            Copy Link
          </button>
          <button
            @click="toggleCompare"
            type="button"
            :class="inCompare ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)]' : 'bg-white border border-[color:var(--brand-primary)] hover:bg-gray-50'"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium border transition-colors"
            :style="inCompare ? '' : 'color: var(--brand-primary);'"
          >
            {{ inCompare ? 'Remove from Compare' : 'Add to Compare' }}
          </button>
          <router-link
            :to="`/lead?product=${product.slug || product.id}`"
            class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-sm btn-brand-primary"
          >
            Apply Now
          </router-link>
          <router-link
            v-if="inCompare"
            to="/compare"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium transition-colors shadow-sm btn-brand-primary"
          >
            Compare Now
          </router-link>
        </div>
      </div>

      <!-- Fixed bottom bar -->
      <div class="fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur-sm border-t shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
          <div class="flex items-center gap-3 min-w-0 flex-1">
            <img
              v-if="product.image_url"
              :src="productImageUrl"
              :alt="product.name"
              class="w-10 h-10 rounded-lg bg-gray-100 object-cover"
            />
            <img
              v-else-if="product.partner?.logo_url"
              :src="partnerLogoUrl"
              :alt="product.partner?.name"
              class="w-10 h-10 rounded-lg bg-gray-100 object-contain"
            />
            <div class="text-sm font-medium text-gray-900 truncate">{{ product.name }}</div>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <button
              @click="toggleCompare"
              type="button"
              :class="inCompare ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)]' : 'bg-white border border-[color:var(--brand-primary)] hover:bg-gray-50'"
              class="px-3 py-2 rounded-lg text-sm font-medium border transition-colors whitespace-nowrap"
              :style="inCompare ? '' : 'color: var(--brand-primary);'"
            >
              {{ inCompare ? 'Remove from Compare' : 'Add to Compare' }}
            </button>
            <router-link
              :to="`/lead?product=${product.slug || product.id}`"
              class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm whitespace-nowrap btn-brand-primary"
            >
              Apply Now
            </router-link>
          </div>
        </div>
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

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { apiService } from '../../services/api';
import { useCompare, useSEO } from '../../composables';
import { getImageUrl, getExcerpt, copyToClipboard } from '../../utils';
import GuestLayout from '../../layouts/GuestLayout.vue';

const route = useRoute();
const product = ref(null);
const attributes = ref([]);
const loading = ref(true);
const activeTab = ref('overview');
const featureQuery = ref('');

const { toggleCompare: toggleCompareAction, isInCompare: checkInCompare } = useCompare();

const inCompare = computed(() => product.value ? checkInCompare(product.value.id) : false);

const tabs = [
  { key: 'overview', label: 'Overview' },
  { key: 'features', label: 'Features' },
  { key: 'eligibility', label: 'Eligibility' },
  { key: 'documents', label: 'Documents' }
];

const productImageUrl = computed(() => {
  return product.value?.image_url ? getImageUrl(product.value.image_url) : null;
});

const partnerLogoUrl = computed(() => {
  return product.value?.partner?.logo_url
    ? getImageUrl(product.value.partner.logo_url, 'https://placehold.co/64x64')
    : 'https://placehold.co/64x64';
});

const filteredAttributes = computed(() => {
  if (!featureQuery.value) return attributes.value;
  const query = featureQuery.value.toLowerCase();
  return attributes.value.filter(attr =>
    (attr.name || '').toLowerCase().includes(query) ||
    (attr.value || '').toLowerCase().includes(query)
  );
});

const toggleCompare = () => {
  if (product.value) {
    toggleCompareAction(product.value.id);
  }
};

const copyLink = async () => {
  const success = await copyToClipboard(window.location.href);
  if (success) {
    // You could use toast here instead of alert
    alert('Link copied to clipboard!');
  } else {
    alert('Failed to copy link. Please copy manually: ' + window.location.href);
  }
};

// SEO setup - will be updated when product loads
const getProductDescription = () => {
  if (!product.value) return '';
  return getExcerpt(product.value.description || '', 160);
};

const getProductKeywords = () => {
  if (!product.value) return [];
  const keywords = [product.value.name];
  if (product.value.partner?.name) keywords.push(product.value.partner.name);
  if (product.value.product_category?.name) keywords.push(product.value.product_category.name);
  return keywords;
};

// Initialize SEO with default values
useSEO({
  title: 'Product Details',
  description: 'View product details and compare financial products',
  type: 'product'
});

// Update SEO when product loads
watch(product, (newProduct) => {
  if (newProduct) {
    useSEO({
      title: newProduct.name || 'Product Details',
      description: getProductDescription() || `Learn more about ${newProduct.name} and compare with other financial products.`,
      image: newProduct.image_url || newProduct.partner?.logo_url,
      keywords: getProductKeywords(),
      type: 'product'
    });
  }
}, { immediate: true });

onMounted(async () => {
  const slug = route.params.slug;

  try {
    const response = await apiService.getProduct(slug);
    product.value = response.data.product;
    attributes.value = response.data.attributes || [];
  } catch (err) {
    console.error('Failed to fetch product:', err);
    // Could set error state here for better UX
  } finally {
    loading.value = false;
  }
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
