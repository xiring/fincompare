<template>
  <Teleport to="body">
    <Transition name="modal">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="close"
      >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
          <div
            class="relative w-full max-w-6xl bg-white rounded-2xl shadow-2xl transform transition-all"
            @click.stop
          >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Products to Compare</h2>
                <p class="mt-1 text-sm text-gray-500">Select products to add to your comparison</p>
              </div>
              <button
                @click="close"
                type="button"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                aria-label="Close modal"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- Filters -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
              <div class="flex flex-col sm:flex-row gap-4 mb-4">
                <div class="flex-1 relative">
                  <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <SearchIcon />
                  </span>
                  <input
                    v-model="searchQuery"
                    @input="debouncedSearch"
                    placeholder="Search products..."
                    :disabled="loading"
                    class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-300 focus-brand disabled:opacity-50 disabled:cursor-not-allowed"
                  />
                </div>
              </div>
              <!-- Category Pills -->
              <div class="flex flex-wrap gap-2">
                <button
                  @click="selectCategory('')"
                  :class="selectedCategory === '' ? 'category-pill-active' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                  class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border font-medium transition-colors"
                >
                  All Categories
                </button>
                <button
                  v-for="cat in categories"
                  :key="cat.id"
                  @click="selectCategory(cat.slug)"
                  :class="selectedCategory === cat.slug ? 'category-pill-active' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                  class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border font-medium transition-colors"
                >
                  <img v-if="cat.image_url" :src="getImageUrl(cat.image_url)" :alt="cat.name" class="w-4 h-4 rounded-full object-cover">
                  <span>{{ cat.name }}</span>
                </button>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6 max-h-[60vh] overflow-y-auto">
              <!-- Loading -->
              <div v-if="loading && products.length === 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="i in 6" :key="i" class="bg-gray-100 rounded-xl p-4 animate-pulse">
                  <div class="h-32 bg-gray-200 rounded mb-3"></div>
                  <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                  <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                </div>
              </div>

              <!-- Products Grid -->
              <div v-else-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                  v-for="product in products"
                  :key="product.id"
                  class="group bg-white border rounded-xl p-4 hover:shadow-md transition-all cursor-pointer"
                  :class="isInCompare(product.id) ? 'ring-2 ring-[color:var(--brand-primary)] bg-amber-50/30' : ''"
                  @click="toggleProduct(product.id)"
                >
                  <div class="flex items-start gap-3 mb-3">
                    <img
                      :src="getProductImage(product)"
                      :alt="product.name"
                      class="w-16 h-16 rounded-lg bg-gray-100 object-cover border border-gray-200 flex-shrink-0"
                    >
                    <div class="flex-1 min-w-0">
                      <h3 class="font-semibold text-gray-900 text-sm leading-tight mb-1 line-clamp-2">
                        {{ product.name }}
                      </h3>
                      <p v-if="product.partner?.name" class="text-xs text-gray-500 truncate">
                        {{ product.partner.name }}
                      </p>
                    </div>
                    <div
                      class="flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition-colors"
                      :class="isInCompare(product.id) ? 'bg-[color:var(--brand-primary)] border-[color:var(--brand-primary)]' : 'border-gray-300'"
                    >
                      <svg
                        v-if="isInCompare(product.id)"
                        class="w-3 h-3 text-white"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="3"
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                      </svg>
                    </div>
                  </div>
                  <div class="flex gap-2 text-xs">
                    <div v-if="product.attribute_highlights?.interest_rate" class="px-2 py-1 bg-gray-50 rounded text-gray-600">
                      Rate: {{ product.attribute_highlights.interest_rate }}
                    </div>
                    <div v-if="product.is_featured" class="px-2 py-1 bg-amber-100 text-amber-700 rounded font-medium">
                      Featured
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty State -->
              <div v-else class="text-center py-12">
                <EmptyBoxIcon className="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-2 text-sm text-gray-500">Try adjusting your search or category filter.</p>
              </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between p-6 border-t border-gray-200 bg-gray-50">
              <div class="text-sm text-gray-600">
                <span class="font-medium">{{ selectedCount }}</span> product{{ selectedCount !== 1 ? 's' : '' }} selected
              </div>
              <div class="flex gap-3">
                <button
                  @click="close"
                  type="button"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                >
                  Cancel
                </button>
                <button
                  @click="addSelected"
                  type="button"
                  :disabled="selectedCount === 0"
                  class="px-4 py-2 text-sm font-semibold text-white bg-[color:var(--brand-primary)] rounded-lg hover:bg-[color:var(--brand-primary-2)] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  style="color: #ffffff !important;"
                >
                  Add to Compare
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Teleport } from 'vue';
import { apiService, default as apiClient } from '../services/api';
import { debounce, getImageUrl } from '../utils/helpers';
import { useCompare } from '../composables/useCompare';
import { SearchIcon, EmptyBoxIcon } from './icons';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'added']);

const { isInCompare: checkInCompare, addToCompare } = useCompare();

const products = ref([]);
const categories = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const selectedCategory = ref('');
const nextPageUrl = ref(null);
const selectedProducts = ref(new Set());

const isInCompare = (productId) => {
  return checkInCompare(productId) || selectedProducts.value.has(productId);
};

const selectedCount = computed(() => {
  return products.value.filter(p => isInCompare(p.id)).length;
});

const getProductImage = (product) => {
  if (product.image_url) return getImageUrl(product.image_url);
  if (product.partner?.logo_url) return getImageUrl(product.partner.logo_url);
  return 'https://placehold.co/64x64';
};

const fetchProducts = async (url = null) => {
  if (loading.value) return;

  loading.value = true;

  try {
    let response;
    if (url) {
      response = await apiClient.get(url);
    } else {
      const params = {};
      if (searchQuery.value) params.q = searchQuery.value;
      if (selectedCategory.value) params.category = selectedCategory.value;
      response = await apiService.getProducts(params);
    }

    if (url) {
      products.value.push(...response.data.products.data);
    } else {
      products.value = response.data.products.data || [];
      categories.value = response.data.categories || [];
    }

    nextPageUrl.value = response.data.products.next_page_url;
  } catch (err) {
    console.error('Failed to fetch products:', err);
  } finally {
    loading.value = false;
  }
};

const selectCategory = (slug) => {
  selectedCategory.value = slug;
  products.value = [];
  fetchProducts();
};

const debouncedSearch = debounce(() => {
  products.value = [];
  fetchProducts();
}, 500);

const toggleProduct = (productId) => {
  if (selectedProducts.value.has(productId)) {
    selectedProducts.value.delete(productId);
  } else {
    selectedProducts.value.add(productId);
  }
};

const addSelected = async () => {
  const toAdd = Array.from(selectedProducts.value);
  for (const productId of toAdd) {
    if (!checkInCompare(productId)) {
      await addToCompare(productId);
    }
  }
  selectedProducts.value.clear();
  emit('added', toAdd);
  close();
};

const close = () => {
  emit('close');
  // Reset state when closing
  setTimeout(() => {
    searchQuery.value = '';
    selectedCategory.value = '';
    selectedProducts.value.clear();
    products.value = [];
  }, 300);
};

// Watch for modal open to fetch products
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    fetchProducts();
  }
});

// Close on Escape key
let escapeHandler = null;
onMounted(() => {
  escapeHandler = (e) => {
    if (e.key === 'Escape' && props.isOpen) {
      close();
    }
  };
  document.addEventListener('keydown', escapeHandler);
});

onUnmounted(() => {
  if (escapeHandler) {
    document.removeEventListener('keydown', escapeHandler);
  }
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .transform,
.modal-leave-active .transform {
  transition: transform 0.3s ease;
}

.modal-enter-from .transform,
.modal-leave-to .transform {
  transform: scale(0.95);
}
</style>

