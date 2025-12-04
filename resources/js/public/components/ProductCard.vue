<template>
  <div class="group bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-xl hover:border-[color:var(--brand-primary)]/30 transition-all duration-300 overflow-hidden flex flex-col h-full">
    <div v-if="product.image_url" class="relative h-52 overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
      <img
        :src="productImageUrl"
        :alt="product.name || TEXT.PRODUCT"
        loading="lazy"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>
    <div class="px-5 pt-5 flex-1 flex flex-col">
      <div class="flex items-start justify-between gap-3 mb-4 min-h-[60px]">
        <div class="flex items-center gap-3 flex-1 min-w-0">
          <a
            v-if="product.partner?.website_url && partnerLogoUrl"
            :href="product.partner.website_url"
            target="_blank"
            rel="noopener noreferrer"
            class="flex-shrink-0 hover:opacity-80 transition-opacity"
          >
            <img
              :src="partnerLogoUrl"
              :alt="product.partner?.name || TEXT.PARTNER"
              loading="lazy"
              class="w-12 h-12 rounded-xl bg-gray-50 object-contain border border-gray-200 p-1.5 flex-shrink-0"
            />
          </a>
          <img
            v-else-if="partnerLogoUrl"
            :src="partnerLogoUrl"
            :alt="product.partner?.name || TEXT.PARTNER"
            loading="lazy"
            class="w-12 h-12 rounded-xl bg-gray-50 object-contain border border-gray-200 p-1.5 flex-shrink-0"
          />
          <div class="min-w-0 mt-2 flex-1 overflow-hidden">
            <router-link
              v-if="product.slug || product.id"
              :to="`/products/${product.slug || product.id}`"
              class="block group-hover:text-[color:var(--brand-primary)] transition-colors"
            >
              <h3 class="font-semibold text-gray-900 text-base leading-tight line-clamp-2">{{ product.name || TEXT.PRODUCT }}</h3>
            </router-link>
            <h3 v-else class="font-semibold text-gray-900 text-base leading-tight line-clamp-2">{{ product.name || TEXT.PRODUCT }}</h3>
            <p v-if="product.partner?.name" class="text-xs text-gray-500 mt-1 truncate">{{ product.partner.name }}</p>
          </div>
        </div>
        <div class="flex mt-2 items-start gap-2 flex-shrink-0 ml-2">
          <span
            v-if="product.is_featured"
            class="px-3 py-1 text-[10px] rounded-full bg-white text-[color:var(--brand-primary)] font-semibold border border-[color:var(--brand-primary)] whitespace-nowrap"
          >
            {{ TEXT.FEATURED }}
          </span>
        </div>
      </div>
      <div v-if="product.attribute_highlights?.interest_rate || product.attribute_highlights?.max_amount" class="mt-auto grid grid-cols-2 gap-3 text-sm">
        <div class="p-4 rounded-xl bg-white border border-gray-200 flex flex-col justify-between min-h-[85px]">
          <div class="text-xs font-medium text-gray-500 mb-2">{{ TEXT.INTEREST_RATE }}</div>
          <div class="font-semibold text-gray-900 text-base leading-tight truncate">
            {{ product.attribute_highlights?.interest_rate || '—' }}
          </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-gray-200 flex flex-col justify-between min-h-[85px]">
          <div class="text-xs font-medium text-gray-500 mb-2">{{ TEXT.MAX_AMOUNT }}</div>
          <div class="font-semibold text-gray-900 text-base leading-tight truncate">
            {{ product.attribute_highlights?.max_amount || '—' }}
          </div>
        </div>
      </div>
    </div>
    <div class="mt-4 px-5 pb-5 pt-4 border-t border-gray-200 flex items-center mb-2 gap-2.5">
      <router-link
        v-if="product.slug || product.id"
        :to="`/products/${product.slug || product.id}`"
        class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition-all duration-200 group shadow-sm hover:shadow-md flex-shrink-0"
        :title="TEXT.VIEW_DETAILS"
      >
        <ArrowRightIcon className="h-5 w-5 transition-transform group-hover:translate-x-1" />
      </router-link>
      <div v-else class="w-10 h-10 flex-shrink-0"></div>
      <router-link
        v-if="product.slug || product.id"
        :to="`/lead?product=${product.slug || product.id}`"
        class="inline-flex items-center justify-center px-4 h-10 rounded-xl bg-[color:var(--brand-primary)] text-white hover:bg-[color:var(--brand-primary-2)] transition-all duration-200 text-sm font-semibold shadow-sm hover:shadow-md flex-1 min-w-0"
        style="color: #ffffff !important;"
      >
        {{ TEXT.APPLY }}
      </router-link>
      <div v-else class="flex-1"></div>
      <button
        type="button"
        @click="handleToggleCompare"
        :aria-label="isInCompare ? TEXT.REMOVE_FROM_COMPARE : TEXT.ADD_TO_COMPARE"
        :title="isInCompare ? TEXT.REMOVE_FROM_COMPARE : TEXT.ADD_TO_COMPARE"
        :class="isInCompare
          ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm hover:shadow-md'
          : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200'"
        class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-sm font-semibold transition-all duration-200 flex-shrink-0"
      >
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path d="M3 3h2v14H3V3zm12 0h2v14h-2V3zM8 7h2v10H8V7zm4 0h2v10h-2V7z"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCompare } from '../composables';
import { getImageUrl, TEXT } from '../utils';
import { ArrowRightIcon } from './icons';

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

