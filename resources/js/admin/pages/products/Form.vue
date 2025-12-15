<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Product' : 'Create Product'" :description="isEdit ? 'Update product information' : 'Add a new product to the catalog'" />

    <LoadingSpinner v-if="isEdit && loading && !product" text="Loading product..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || product">
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
                :error="getError(errors, 'name')"
              />

              <FormInput
                id="slug"
                v-model="form.slug"
                label="Slug"
                hint="Leave empty to auto-generate from name"
                :error="getError(errors, 'slug')"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormSelect
                  id="partner_id"
                  v-model="form.partner_id"
                  label="Partner"
                  :options="partners"
                  required
                  :error="getError(errors, 'partner_id')"
                />

                <FormSelect
                  id="product_category_id"
                  v-model="form.product_category_id"
                  label="Category"
                  :options="categories"
                  required
                  :error="getError(errors, 'product_category_id')"
                />
              </div>

              <FormTextarea
                id="description"
                v-model="form.description"
                label="Description"
                :rows="4"
                :error="getError(errors, 'description')"
              />

              <!-- Image with existing image display (Edit mode only) -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-charcoal-700 mb-2">
                  Product Image
                </label>
                <div v-if="isEdit && product && product.image && !imagePreview" class="mt-2 mb-2">
                  <img :src="`/storage/${product.image}`" alt="Current image" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
                  <p class="mt-1 text-xs text-charcoal-500">Current image</p>
                </div>
                <FormFileInput
                  id="image"
                  v-model="form.image"
                  accept="image/*"
                  :hint="isEdit ? 'JPG, PNG, GIF or WebP. Max size: 5MB. Leave empty to keep current image.' : 'JPG, PNG, GIF or WebP. Max size: 5MB'"
                  :preview="true"
                  :error="getError(errors, 'image')"
                />
                <div v-if="imagePreview" class="mt-2">
                  <p class="text-xs text-charcoal-500">New image preview:</p>
                  <img :src="imagePreview" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-charcoal-200" />
                </div>
              </div>

              <FormCheckbox
                id="is_featured"
                v-model="form.is_featured"
                label="Featured"
                :options="[{ id: true, name: 'Featured' }]"
              />

              <FormSelect
                id="status"
                v-model="form.status"
                label="Status"
                :options="statusOptions"
                required
                :error="getError(errors, 'status')"
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
          :submit-text="isEdit ? 'Update Product' : 'Save Product'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/products"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch, watchEffect, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import { ConstantOptions } from '../../constants/ConstantOptions';
import {
  useProductCreateMutation,
  useProductDetailQuery,
  useProductUpdateMutation,
} from '../../queries/products';
import { usePartnerListQuery } from '../../queries/partners';
import { useProductCategoryListQuery } from '../../queries/productCategories';
import { useAttributeListQuery } from '../../queries/attributes';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormSection from '../../components/FormSection.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormFileInput from '../../components/FormFileInput.vue';
import FormActions from '../../components/FormActions.vue';
import AttributeInput from '../../components/AttributeInput.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { Attribute, Partner, ProductCategory, FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const productId = route.params.id as string | undefined;

const isEdit = computed(() => !!productId);
const {
  data: productData,
  isLoading: detailLoading,
  error: detailError,
} = useProductDetailQuery(computed(() => (isEdit.value ? productId : undefined)));
const createMutation = useProductCreateMutation();
const updateMutation = useProductUpdateMutation();
const loading = computed(() => {
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value;
  return createMutation.isPending.value;
});
const product = computed(() => productData.value || null);

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

const { data: partnersData } = usePartnerListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const { data: categoriesData } = useProductCategoryListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const partners = computed<Partner[]>(() => (partnersData.value?.items || []) as Partner[]);
const categories = computed<ProductCategory[]>(() => (categoriesData.value?.items || []) as ProductCategory[]);
const attributes = ref<Attribute[]>([]);
const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const loadingAttributes = computed(() => attributesLoading.value || attributesFetching.value);
const imagePreview = ref<string | null>(null);

const statusOptions = ConstantOptions.productStatuses();

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

const attributesParams = computed(() => ({
  product_category_id: form.product_category_id || undefined,
  per_page: 500,
  sort: 'id',
  dir: 'asc',
}));

const { data: attributesData, isLoading: attributesLoading, isFetching: attributesFetching } = useAttributeListQuery(
  attributesParams,
  { enabled: computed(() => !!form.product_category_id) }
);

watchEffect(() => {
  const attrs = (attributesData.value?.items || []) as Attribute[];
  attributes.value = attrs;
});

const loadAttributesFromProduct = () => {
  const attrs = attributes.value;
  const currentItem = product.value as any;
  const attributeValues: AttributeValue[] = currentItem?.attributeValues || currentItem?.attribute_values || [];

  if (isEdit.value) {
    attrs.forEach((attr) => {
      if (!(attr.id in form.attributes)) {
        const existingValue = attributeValues.find((av) => av.attribute_id === attr.id);
        if (existingValue) {
          const scalarValue = getScalarValue(existingValue);
          form.attributes[attr.id] = scalarValue !== null && scalarValue !== undefined ? scalarValue : '';
        } else {
          form.attributes[attr.id] = '';
        }
      }
    });
  } else {
    attrs.forEach((attr) => {
      if (!(attr.id in form.attributes)) {
        form.attributes[attr.id] = '';
      }
    });
  }
};

watchEffect(() => {
  if (attributes.value.length === 0) return;
  loadAttributesFromProduct();
});

watch(
  () => form.product_category_id,
  () => {
    form.attributes = {};
  }
);

const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load product';
});

watchEffect(() => {
  if (product.value) {
    const currentItem = product.value as any;
    form.name = currentItem?.name || '';
    form.slug = currentItem?.slug || '';
    form.partner_id = String(currentItem?.partner_id || '');
    form.product_category_id = String(currentItem?.product_category_id || '');
    form.description = currentItem?.description || '';
    form.is_featured = currentItem?.is_featured || false;
    form.status = currentItem?.status || 'active';

    const attributeValues: AttributeValue[] = currentItem?.attributeValues || currentItem?.attribute_values || [];
    attributeValues.forEach((av) => {
      const scalarValue = getScalarValue(av);
      if (scalarValue !== null && scalarValue !== undefined) {
        form.attributes[av.attribute_id] = scalarValue;
      }
    });
    loadAttributesFromProduct();
  } else {
    form.name = '';
    form.slug = '';
    form.partner_id = '';
    form.product_category_id = '';
    form.description = '';
    form.is_featured = false;
    form.status = 'active';
    form.attributes = {};
  }

  if (detailErrorMessage.value) {
    errorMessage.value = detailErrorMessage.value;
  }
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

    if (isEdit.value && productId) {
      await updateMutation.mutateAsync({ id: productId, payload: data });
      successMessage.value = 'Product updated successfully!';
    } else {
      await createMutation.mutateAsync(data);
      successMessage.value = 'Product created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/products');
    }, 1500);
  } catch (error: unknown) {
    const err = error as any;
    if (err.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value = err.response?.data?.message || (isEdit.value ? 'Failed to update product' : 'Failed to create product');
    }
  }
};

onMounted(() => {
  if (!isEdit.value && form.product_category_id) {
    loadAttributesFromProduct();
  }
});
</script>

