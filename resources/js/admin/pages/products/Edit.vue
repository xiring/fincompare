<template>
  <div>
    <PageHeader title="Edit Product" description="Update product information" />

    <LoadingSpinner v-if="loading && !productsStore.currentItem" text="Loading product..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="productsStore.currentItem">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Left Column: Basic Product Information -->
          <div class="space-y-6">
            <FormSection title="Product Information">
              <FormInput
                id="name"
                v-model="form.name"
                label="Name"
                type="text"
                required
                :error="getError('name')"
              />

              <FormInput
                id="slug"
                v-model="form.slug"
                label="Slug"
                hint="Leave empty to auto-generate from name"
                :error="getError('slug')"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormSelect
                  id="partner_id"
                  v-model="form.partner_id"
                  label="Partner"
                  :options="partners"
                  required
                  :error="getError('partner_id')"
                />

                <FormSelect
                  id="product_category_id"
                  v-model="form.product_category_id"
                  label="Category"
                  :options="categories"
                  required
                  :error="getError('product_category_id')"
                />
              </div>

              <FormTextarea
                id="description"
                v-model="form.description"
                label="Description"
                :rows="4"
                :error="getError('description')"
              />

              <!-- Image with existing image display -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-charcoal-700 mb-2">
                  Product Image
                </label>
                <div v-if="product && product.image && !imagePreview" class="mt-2 mb-2">
                  <img :src="`/storage/${product.image}`" alt="Current image" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
                  <p class="mt-1 text-xs text-charcoal-500">Current image</p>
                </div>
                <FormFileInput
                  id="image"
                  v-model="form.image"
                  accept="image/*"
                  hint="JPG, PNG, GIF or WebP. Max size: 5MB. Leave empty to keep current image."
                  :preview="true"
                  :error="getError('image')"
                />
                <div v-if="imagePreview" class="mt-2">
                  <p class="text-xs text-charcoal-500">New image preview:</p>
                  <img :src="imagePreview" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
                </div>
              </div>

              <div class="mb-6">
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
              </div>

              <FormSelect
                id="status"
                v-model="form.status"
                label="Status"
                :options="statusOptions"
                required
                :error="getError('status')"
              />
            </FormSection>
          </div>

          <!-- Right Column: Product Attributes -->
          <div class="space-y-6">
            <FormSection title="Product Attributes" description="Add attribute values for this product">
              <LoadingSpinner v-if="loadingAttributes" text="Loading attributes..." />
              <div v-else-if="attributes.length === 0" class="text-sm text-charcoal-500">
                Select a category to see available attributes
              </div>
              <div v-else class="space-y-4">
                <div
                  v-for="attr in attributes"
                  :key="attr.id"
                  class="bg-charcoal-50 p-4 rounded-lg"
                >
                  <label class="block text-sm font-medium text-charcoal-700 mb-2">
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
            </FormSection>
          </div>
        </div>

        <FormActions
          :loading="loading"
          submit-text="Update Product"
          loading-text="Updating..."
          cancel-route="/admin/products"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductsStore } from '../../stores';
import { usePartnersStore } from '../../stores';
import { useProductCategoriesStore } from '../../stores';
import { adminApi } from '../../services/api';
import { extractValidationErrors } from '../../utils/validation';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormSection from '../../components/FormSection.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormActions from '../../components/FormActions.vue';
import AttributeInput from '../../components/AttributeInput.vue';
import type { Attribute, Partner, ProductCategory, FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const productId = route.params.id as string;

const productsStore = useProductsStore();
const partnersStore = usePartnersStore();
const productCategoriesStore = useProductCategoriesStore();

// Use store loading state and current item
const loading = computed(() => productsStore.loading);
const product = computed(() => productsStore.currentItem);

interface ProductFormData {
  name: string;
  slug: string;
  partner_id: string;
  product_category_id: string;
  description: string;
  image: File | null;
  is_featured: boolean;
  status: 'active' | 'inactive';
  attributes: Record<string, any>;
}

const form = reactive<ProductFormData>({
  name: '',
  slug: '',
  partner_id: '',
  product_category_id: '',
  description: '',
  image: null,
  is_featured: false,
  status: 'active',
  attributes: {},
});

const partners = ref<Partner[]>([]);
const categories = ref<ProductCategory[]>([]);
const attributes = ref<Attribute[]>([]);
const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loadingAttributes = ref<boolean>(false);
const imagePreview = ref<string | null>(null);

const statusOptions = [
  { id: 'active', name: 'Active' },
  { id: 'inactive', name: 'Inactive' },
];

// Helper to get first error string from errors object
const getError = (field: string): string | undefined => {
  const error = errors.value[field];
  if (!error) return undefined;
  return Array.isArray(error) ? error[0] : error;
};

// Watch for image changes to show preview
watch(() => form.image, (newFile) => {
  if (newFile) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(newFile);
  } else {
    imagePreview.value = null;
  }
});

interface AttributeValue {
  attribute_id: number;
  value_number?: number | null;
  value_boolean?: boolean | null;
  value_text?: string | null;
  value_json?: any;
}

// Helper function to extract scalar value from attribute value object
const getScalarValue = (attributeValue: AttributeValue | null | undefined): any => {
  if (!attributeValue) return null;

  // Check value_number first (most common for numeric attributes)
  if (attributeValue.value_number !== null && attributeValue.value_number !== undefined) {
    return attributeValue.value_number;
  }

  // Check value_boolean
  if (attributeValue.value_boolean !== null && attributeValue.value_boolean !== undefined) {
    return attributeValue.value_boolean;
  }

  // Check value_text
  if (attributeValue.value_text !== null && attributeValue.value_text !== undefined) {
    return attributeValue.value_text;
  }

  // Check value_json
  if (attributeValue.value_json !== null && attributeValue.value_json !== undefined) {
    return attributeValue.value_json;
  }

  return null;
};

const loadAttributes = async (): Promise<void> => {
  const categoryId = form.product_category_id;
  if (!categoryId) {
    attributes.value = [];
    return;
  }

  loadingAttributes.value = true;
  try {
    const categoryIdStr = String(categoryId);
    const response = await adminApi.attributes.byCategory(categoryIdStr);
    attributes.value = (Array.isArray(response.data) ? response.data : response.data?.data || []) as Attribute[];

    // Initialize attribute values from existing product or empty
    const currentItem = productsStore.currentItem as any;
    const attributeValues: AttributeValue[] = currentItem?.attributeValues || currentItem?.attribute_values || [];
    attributes.value.forEach((attr) => {
      if (!(attr.id in form.attributes)) {
        // Try to get existing value from product
        const existingValue = attributeValues.find((av) => av.attribute_id === attr.id);

        if (existingValue) {
          const scalarValue = getScalarValue(existingValue);
          form.attributes[attr.id] = scalarValue !== null && scalarValue !== undefined ? scalarValue : '';
        } else {
          form.attributes[attr.id] = '';
        }
      }
    });
  } catch (error: any) {
    console.error('Error loading attributes:', error);
    errorMessage.value = 'Failed to load attributes';
  } finally {
    loadingAttributes.value = false;
  }
};

// Watch for category changes to load attributes
watch(() => form.product_category_id, () => {
  loadAttributes();
});

const loadProduct = async (): Promise<void> => {
  try {
    await productsStore.fetchItem(productId);

    // Populate form
    const currentItem = productsStore.currentItem as any;
    form.name = currentItem?.name || '';
    form.slug = currentItem?.slug || '';
    form.partner_id = String(currentItem?.partner_id || '');
    form.product_category_id = String(currentItem?.product_category_id || '');
    form.description = currentItem?.description || '';
    form.is_featured = currentItem?.is_featured || false;
    form.status = currentItem?.status || 'active';

    // Initialize attribute values from existing product data
    const attributeValues: AttributeValue[] = currentItem?.attributeValues || currentItem?.attribute_values || [];
    if (attributeValues.length > 0) {
      attributeValues.forEach((av) => {
        const scalarValue = getScalarValue(av);
        if (scalarValue !== null && scalarValue !== undefined) {
          form.attributes[av.attribute_id] = scalarValue;
        }
      });
    }

    // Load attributes if category is set (this will also populate any missing attribute values)
    if (form.product_category_id) {
      await loadAttributes();
    }
  } catch (error: any) {
    console.error('Error loading product:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Product not found';
    } else {
      errorMessage.value = 'Failed to load product';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const data: any = {
      name: form.name,
      slug: form.slug,
      partner_id: typeof form.partner_id === 'string' ? parseInt(form.partner_id) : form.partner_id,
      product_category_id: typeof form.product_category_id === 'string' ? parseInt(form.product_category_id) : form.product_category_id,
      description: form.description,
      is_featured: form.is_featured ? 1 : 0,
      status: form.status,
      attributes: form.attributes,
    };

    // Only include image if a new file was selected
    if (form.image) {
      data.image = form.image;
    }

    await productsStore.updateItem(productId, data);
    successMessage.value = 'Product updated successfully!';
    setTimeout(() => {
      router.push('/admin/products');
    }, 1500);
  } catch (error: any) {
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
      productCategoriesStore.fetchItems({ per_page: 1000 }),
    ]);
    partners.value = partnersStore.items;
    categories.value = productCategoriesStore.items;

    // Load product
    await loadProduct();
  } catch (error: any) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
