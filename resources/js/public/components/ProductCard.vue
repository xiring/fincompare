<template>
  <div class="group bg-white border rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
    <div v-if="product.image_url" class="relative h-48 overflow-hidden bg-gray-100">
      <img
        :src="productImageUrl"
        :alt="product.name || 'Product'"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
      />
    </div>
    <div class="px-4 pt-4">
      <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-3">
          <a
            v-if="product.partner?.website_url"
            :href="product.partner.website_url"
            target="_blank"
            rel="noopener noreferrer"
            class="hover:opacity-80 transition-opacity"
          >
            <img
              :src="partnerLogoUrl"
              :alt="product.partner?.name || 'Partner'"
              class="w-12 h-12 rounded bg-gray-100 object-contain"
            />
          </a>
          <img
            v-else
            :src="partnerLogoUrl"
            :alt="product.partner?.name || 'Partner'"
            class="w-12 h-12 rounded bg-gray-100 object-contain"
          />
          <div>
            <router-link :to="`/products/${product.slug || product.id}`" class="block hover:underline">
              <h3 class="font-semibold text-gray-900">{{ product.name || 'Product' }}</h3>
            </router-link>
            <p class="text-xs text-gray-500">{{ product.partner?.name || 'Partner' }}</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <span
            v-if="product.is_featured"
            class="px-2 py-0.5 text-[10px] rounded-full bg-amber-100 text-amber-700 font-semibold"
          >
            Featured
          </span>
          <button
            type="button"
            title="Add to compare"
            @click="handleToggleCompare"
            :class="isInCompare ? 'text-amber-600' : 'text-gray-400 hover:text-gray-600'"
            class="p-2 rounded-full bg-gray-50"
          >
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M3 3h2v14H3V3zm12 0h2v14h-2V3zM8 7h2v10H8V7zm4 0h2v10h-2V7z"/>
            </svg>
          </button>
        </div>
      </div>
      <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
        <div class="p-3 rounded-lg bg-gray-50">
          <div class="text-xs text-gray-500">Interest Rate</div>
          <div class="font-medium text-gray-900">
            {{ product.attribute_highlights?.interest_rate || '—' }}
          </div>
        </div>
        <div class="p-3 rounded-lg bg-gray-50">
          <div class="text-xs text-gray-500">Max Amount</div>
          <div class="font-medium text-gray-900">
            {{ product.attribute_highlights?.max_amount || '—' }}
          </div>
        </div>
      </div>
    </div>
    <div class="mt-4 px-4 pb-4 flex items-center justify-between">
      <router-link
        :to="`/products/${product.slug || product.id}`"
        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition-all duration-200 group"
        title="View details"
      >
        <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </router-link>
      <div class="flex gap-2">
        <router-link
          :to="`/lead?product=${product.slug || product.id}`"
          class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-[color:var(--brand-primary)]/20 bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition-colors text-sm font-medium"
        >
          Apply
        </router-link>
        <button
          type="button"
          @click="handleToggleCompare"
          :class="isInCompare ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
          class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium transition-colors"
        >
          {{ isInCompare ? 'In Compare' : 'Compare' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCompare } from '../composables';
import { getImageUrl } from '../utils';

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const { toggleCompare, isInCompare: checkInCompare } = useCompare();

const isInCompare = computed(() => checkInCompare(props.product.id));

const productImageUrl = computed(() => {
  return props.product.image_url ? getImageUrl(props.product.image_url) : null;
});

const partnerLogoUrl = computed(() => {
  return props.product.partner?.logo_url
    ? getImageUrl(props.product.partner.logo_url, 'https://placehold.co/48x48')
    : 'https://placehold.co/48x48';
});

const handleToggleCompare = () => {
  toggleCompare(props.product.id);
};
</script>

