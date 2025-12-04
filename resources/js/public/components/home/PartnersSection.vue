<template>
  <section
    ref="sectionRef"
    :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'"
    class="py-12"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-xl font-semibold mb-6">{{ title }}</h2>
      <div v-if="loading && partners.length === 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 items-center">
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
            :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="h-12 flex items-center justify-center bg-white border rounded hover:shadow-md transition-shadow cursor-pointer"
          >
            <img
              :src="getImageUrl(partner.logo_url) || 'https://placehold.co/120x30?text=Logo'"
              :alt="partner.name || 'Partner'"
              loading="lazy"
              class="max-h-8"
            />
          </a>
          <div
            v-else-if="partner"
            :style="{ animationDelay: `${index * 80}ms` }"
            :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'"
            class="h-12 flex items-center justify-center bg-white border rounded"
          >
            <img
              :src="getImageUrl(partner.logo_url) || 'https://placehold.co/120x30?text=Logo'"
              :alt="partner.name || 'Partner'"
              loading="lazy"
              class="max-h-8"
            />
          </div>
        </template>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { getImageUrl, TEXT } from '../../utils';

const props = defineProps({
  partners: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  visible: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: TEXT.SECTION_TRUSTED_PARTNERS
  }
});

const sectionRef = ref(null);

// Filter out any invalid partners
const validPartners = computed(() => {
  return (props.partners || []).filter(p => p && (p.id || p.name));
});
</script>

