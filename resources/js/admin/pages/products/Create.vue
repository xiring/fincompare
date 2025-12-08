<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-charcoal-800">Create Product</h1>
      <p class="mt-1 text-sm text-charcoal-600">Add a new product to the catalog</p>
    </div>

    <!-- Error Message -->
    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />

    <!-- Success Message -->
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <!-- Form -->
    <FormCard>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Column: Basic Product Information -->
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-charcoal-800">Product Information</h3>
            </div>

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-charcoal-700">
                Name <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
                :class="{ 'border-red-300': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <!-- Slug -->
            <div>
              <label for="slug" class="block text-sm font-medium text-charcoal-700">
                Slug (optional)
              </label>
              <input
                id="slug"
                v-model="form.slug"
                type="text"
                class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
                :class="{ 'border-red-300': errors.slug }"
              />
              <p v-if="errors.slug" class="mt-1 text-sm text-red-600">{{ errors.slug }}</p>
              <p class="mt-1 text-xs text-charcoal-500">Leave empty to auto-generate from name</p>
            </div>

            <!-- Partner & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="partner_id" class="block text-sm font-medium text-charcoal-700">
                  Partner <span class="text-red-500">*</span>
                </label>
                <select
                  id="partner_id"
                  v-model="form.partner_id"
                  required
                  class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
                  :class="{ 'border-red-300': errors.partner_id }"
                >
                  <option value="">-- Select Partner --</option>
                  <option v-for="partner in partners" :key="partner.id" :value="partner.id">
                    {{ partner.name }}
                  </option>
                </select>
                <p v-if="errors.partner_id" class="mt-1 text-sm text-red-600">{{ errors.partner_id }}</p>
              </div>
              <div>
                <label for="product_category_id" class="block text-sm font-medium text-charcoal-700">
                  Category <span class="text-red-500">*</span>
                </label>
                <select
                  id="product_category_id"
                  v-model="form.product_category_id"
                  required
                  @change="loadAttributes"
                  class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
                  :class="{ 'border-red-300': errors.product_category_id }"
                >
                  <option value="">-- Select Category --</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.product_category_id" class="mt-1 text-sm text-red-600">{{ errors.product_category_id }}</p>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-charcoal-700">
                Description
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
              ></textarea>
            </div>

            <!-- Image -->
            <div>
              <label for="image" class="block text-sm font-medium text-charcoal-700">
                Product Image
              </label>
              <input
                id="image"
                type="file"
                accept="image/*"
                @change="handleImageChange"
                class="block w-full text-sm text-charcoal-500"
              />
              <p class="mt-1 text-xs text-charcoal-500">JPG, PNG, GIF or WebP. Max size: 5MB</p>
              <div v-if="imagePreview" class="mt-2">
                <img :src="imagePreview" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
              </div>
              <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</p>
            </div>

            <!-- Featured -->
            <div class="flex items-center gap-3">
              <input
                id="is_featured"
                v-model="form.is_featured"
                type="checkbox"
                class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
              />
              <label for="is_featured" class="block text-sm font-medium text-charcoal-700">
                Featured
              </label>
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-charcoal-700">
                Status <span class="text-red-500">*</span>
              </label>
              <select
                id="status"
                v-model="form.status"
                required
                class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
                :class="{ 'border-red-300': errors.status }"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
            </div>
          </div>

          <!-- Right Column: Product Attributes -->
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-charcoal-800">Product Attributes</h3>
              <p class="text-sm text-charcoal-600">Add attribute values for this product</p>
            </div>

            <LoadingSpinner v-if="loadingAttributes" text="Loading attributes..." />
            <div v-else-if="attributes.length === 0" class="text-sm text-charcoal-500">
              Select a category to see available attributes
            </div>
            <div v-else class="space-y-4">
              <div
                v-for="attr in attributes"
                :key="attr.id"
                class="bg-charcoal-50"
              >
                <label class="block text-sm font-medium text-charcoal-700">
                  {{ attr.name }}
                  <span v-if="attr.is_required" class="text-red-500">*</span>
                  <span v-if="attr.unit" class="text-charcoal-500">({{ attr.unit }})</span>
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
        <div class="flex items-center gap-3 pt-4 border-t border-charcoal-200">
          <router-link
            to="/admin/products"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-white"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            :disabled="loading"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-500 text-white rounded-lg font-medium text-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <LoadingSpinner v-if="loading" spinner-class="h-4 w-4 mr-2" container-class="py-0" />
            <span>{{ loading ? 'Creating...' : 'Save Product' }}</span>
          </button>
        </div>
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useProductsStore } from '../../stores';
import { usePartnersStore } from '../../stores';
import { useProductCategoriesStore } from '../../stores';
import { adminApi } from '../../services/api';
import { extractValidationErrors, formatValidationError } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import AttributeInput from '../../components/AttributeInput.vue';
import FormCard from '../../components/FormCard.vue';

const router = useRouter();
const productsStore = useProductsStore();
const partnersStore = usePartnersStore();
const productCategoriesStore = useProductCategoriesStore();

// Use store loading state
const loading = computed(() => productsStore.loading);

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
    // Initialize attribute values
    attributes.value.forEach(attr => {
      if (!(attr.id in form.attributes)) {
        form.attributes[attr.id] = '';
      }
    });
  } catch (error) {
    console.error('Error loading attributes:', error);
    errorMessage.value = 'Failed to load attributes';
  } finally {
    loadingAttributes.value = false;
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  loading.value = true;

  try {
    // Auto-generate slug if not provided
    if (!form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    const data = {
      ...form,
      partner_id: parseInt(form.partner_id),
      product_category_id: parseInt(form.product_category_id),
      is_featured: form.is_featured ? 1 : 0,
      attributes: form.attributes
    };

    await productsStore.createItem(data);
    successMessage.value = 'Product created successfully!';
    setTimeout(() => {
      router.push('/admin/products');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create product';
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
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
