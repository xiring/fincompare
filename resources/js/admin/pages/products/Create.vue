<template>
  <div>
    <PageHeader title="Create Product" description="Add a new product to the catalog" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
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

              <FormFileInput
                id="image"
                v-model="form.image"
                label="Product Image"
                accept="image/*"
                hint="JPG, PNG, GIF or WebP. Max size: 5MB"
                :preview="true"
                :error="getError('image')"
              />

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
          submit-text="Save Product"
          loading-text="Creating..."
          cancel-route="/admin/products"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
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

const router = useRouter();
const productsStore = useProductsStore();
const partnersStore = usePartnersStore();
const productCategoriesStore = useProductCategoriesStore();

// Use store loading state
const loading = computed(() => productsStore.loading);

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
    // Initialize attribute values
    attributes.value.forEach((attr) => {
      if (!(attr.id in form.attributes)) {
        form.attributes[attr.id] = '';
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

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    // Auto-generate slug if not provided
    if (!form.slug && form.name) {
      form.slug = form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

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

    await productsStore.createItem(data);
    successMessage.value = 'Product created successfully!';
    setTimeout(() => {
      router.push('/admin/products');
    }, 1500);
  } catch (error: any) {
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
      productCategoriesStore.fetchItems({ per_page: 1000 }),
    ]);
    partners.value = partnersStore.items;
    categories.value = productCategoriesStore.items;
  } catch (error: any) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
