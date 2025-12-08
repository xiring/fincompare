<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Product</h1>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update product information</p>
    </div>

    <!-- Loading State -->
    <LoadingSpinner v-if="loading && !productsStore.currentItem" text="Loading product..." />

    <!-- Error Message -->
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />

    <!-- Success Message -->
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <!-- Form -->
    <div v-if="productsStore.currentItem" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Column: Basic Product Information -->
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product Information</h3>
            </div>

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Name <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.name }}</p>
            </div>

            <!-- Slug -->
            <div>
              <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Slug (optional)
              </label>
              <input
                id="slug"
                v-model="form.slug"
                type="text"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.slug }"
              />
              <p v-if="errors.slug" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.slug }}</p>
            </div>

            <!-- Partner & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="partner_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Partner <span class="text-red-500">*</span>
                </label>
                <select
                  id="partner_id"
                  v-model="form.partner_id"
                  required
                  class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                  :class="{ 'border-red-300 dark:border-red-600': errors.partner_id }"
                >
                  <option value="">-- Select Partner --</option>
                  <option v-for="partner in partners" :key="partner.id" :value="partner.id">
                    {{ partner.name }}
                  </option>
                </select>
                <p v-if="errors.partner_id" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.partner_id }}</p>
              </div>
              <div>
                <label for="product_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Category <span class="text-red-500">*</span>
                </label>
                <select
                  id="product_category_id"
                  v-model="form.product_category_id"
                  required
                  @change="loadAttributes"
                  class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                  :class="{ 'border-red-300 dark:border-red-600': errors.product_category_id }"
                >
                  <option value="">-- Select Category --</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.product_category_id" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.product_category_id }}</p>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Description
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
              ></textarea>
            </div>

            <!-- Image -->
            <div>
              <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Product Image
              </label>
              <div v-if="product.image && !imagePreview" class="mt-2 mb-2">
                <img :src="`/storage/${product.image}`" alt="Current image" class="h-32 w-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current image</p>
              </div>
              <input
                id="image"
                type="file"
                accept="image/*"
                @change="handleImageChange"
                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400"
              />
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, GIF or WebP. Max size: 5MB. Leave empty to keep current image.</p>
              <div v-if="imagePreview" class="mt-2">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">New image preview:</p>
                <img :src="imagePreview" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700" />
              </div>
              <p v-if="errors.image" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.image }}</p>
            </div>

            <!-- Featured -->
            <div class="flex items-center gap-3">
              <input
                id="is_featured"
                v-model="form.is_featured"
                type="checkbox"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
              />
              <label for="is_featured" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Featured
              </label>
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status <span class="text-red-500">*</span>
              </label>
              <select
                id="status"
                v-model="form.status"
                required
                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-300 dark:border-red-600': errors.status }"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <p v-if="errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.status }}</p>
            </div>
          </div>

          <!-- Right Column: Product Attributes -->
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product Attributes</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Add attribute values for this product</p>
            </div>

            <LoadingSpinner v-if="loadingAttributes" text="Loading attributes..." />
            <div v-else-if="attributes.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
              Select a category to see available attributes
            </div>
            <div v-else class="space-y-4">
              <div
                v-for="attr in attributes"
                :key="attr.id"
                class="bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4"
              >
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  {{ attr.name }}
                  <span v-if="attr.is_required" class="text-red-500">*</span>
                  <span v-if="attr.unit" class="text-gray-500 dark:text-gray-400 text-xs">({{ attr.unit }})</span>
                </label>
                <AttributeInput
                  :attr="attr"
                  :model-value="form.attributes[attr.id]"
                  :partners="partners"
                  @update:model-value="form.attributes[attr.id] = $event"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
          <router-link
            to="/admin/products"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
            <span>{{ loading ? 'Updating...' : 'Update Product' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductsStore } from '../../stores';
import { usePartnersStore } from '../../stores';
import { useProductCategoriesStore } from '../../stores';
import { adminApi } from '../../services/api';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import AttributeInput from '../../components/AttributeInput.vue';

const route = useRoute();
const router = useRouter();
const productId = route.params.id;

const productsStore = useProductsStore();
const partnersStore = usePartnersStore();
const productCategoriesStore = useProductCategoriesStore();

// Use store loading state and current item
const loading = computed(() => productsStore.loading);
const product = computed(() => productsStore.currentItem);

const form = reactive({
  name: '',
  slug: '',
  partner_id: '',
  product_category_id: '',
  description: '',
  image: null,
  is_featured: false,
  status: 'active',
  attributes: {}
});

const partners = ref([]);
const categories = ref([]);
const attributes = ref([]);
const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loadingAttributes = ref(false);
const imagePreview = ref(null);

const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.image = null;
    imagePreview.value = null;
  }
};

const loadAttributes = async () => {
  if (!form.product_category_id) {
    attributes.value = [];
    return;
  }

  loadingAttributes.value = true;
  try {
    const response = await adminApi.attributes.byCategory(form.product_category_id);
    attributes.value = response.data || [];

    // Initialize attribute values from existing product or empty
    attributes.value.forEach(attr => {
      if (!(attr.id in form.attributes)) {
        // Try to get existing value from product
        const existingValue = productsStore.currentItem?.attribute_values?.find(av => av.attribute_id === attr.id);
        if (existingValue) {
          form.attributes[attr.id] = existingValue.value || existingValue.getScalarValue?.() || '';
        } else {
          form.attributes[attr.id] = '';
        }
      }
    });
  } catch (error) {
    console.error('Error loading attributes:', error);
    errorMessage.value = 'Failed to load attributes';
  } finally {
    loadingAttributes.value = false;
  }
};

const loadProduct = async () => {
  try {
    await productsStore.fetchItem(productId);

    // Populate form
    form.name = productsStore.currentItem?.name || '';
    form.slug = productsStore.currentItem?.slug || '';
    form.partner_id = productsStore.currentItem?.partner_id || '';
    form.product_category_id = productsStore.currentItem?.product_category_id || '';
    form.description = productsStore.currentItem?.description || '';
    form.is_featured = productsStore.currentItem?.is_featured || false;
    form.status = productsStore.currentItem?.status || 'active';

    // Load attributes if category is set
    if (form.product_category_id) {
      await loadAttributes();
    }
  } catch (error) {
    console.error('Error loading product:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Product not found';
    } else {
      errorMessage.value = 'Failed to load product';
    }
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const data = {
      ...form,
      partner_id: parseInt(form.partner_id),
      product_category_id: parseInt(form.product_category_id),
      is_featured: form.is_featured ? 1 : 0,
      attributes: form.attributes
    };

    await productsStore.updateItem(productId, data);
    successMessage.value = 'Product updated successfully!';
    setTimeout(() => {
      router.push('/admin/products');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update product';
    }
  }
};

onMounted(async () => {
  try {
    // Load partners and categories using stores (fetch all items for dropdowns)
    await Promise.all([
      partnersStore.fetchItems({ per_page: 1000 }),
      productCategoriesStore.fetchItems({ per_page: 1000 })
    ]);
    partners.value = partnersStore.items;
    categories.value = productCategoriesStore.items;

    // Load product
    await loadProduct();
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
