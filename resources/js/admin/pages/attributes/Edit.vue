<template>
  <div>
    <PageHeader title="Edit Attribute" description="Update attribute information" />

    <LoadingSpinner v-if="loading && !attribute" text="Loading attribute..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="attribute">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          :error="errors.name"
        />

        <FormSelect
          id="data_type"
          v-model="form.data_type"
          label="Data Type"
          :options="typeOptions"
          required
          :error="errors.data_type"
        />

        <FormSelect
          id="product_category_id"
          v-model="form.product_category_id"
          label="Product Category"
          :options="categoryOptions"
          placeholder="All Categories"
          :error="errors.product_category_id"
        />

        <FormActions
          :loading="loading"
          submit-text="Update Attribute"
          loading-text="Updating..."
          cancel-route="/admin/attributes"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAttributesStore, useProductCategoriesStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormSelect from '../../components/FormSelect.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const route = useRoute();
const router = useRouter();
const attributeId = route.params.id;

const attributesStore = useAttributesStore();
const productCategoriesStore = useProductCategoriesStore();
const attribute = computed(() => attributesStore.currentItem);

const form = reactive({
  name: '',
  data_type: 'text',
  product_category_id: null
});

const typeOptions = [
  { id: 'text', name: 'Text' },
  { id: 'number', name: 'Number' },
  { id: 'percentage', name: 'Percentage' },
  { id: 'boolean', name: 'Boolean' },
  { id: 'json', name: 'JSON' }
];

const categories = computed(() => productCategoriesStore.items);
const categoryOptions = computed(() => {
  return [{ id: null, name: 'All Categories' }, ...categories.value];
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = computed(() => attributesStore.loading || productCategoriesStore.loading);

const loadAttribute = async () => {
  try {
    await attributesStore.fetchItem(attributeId);
    if (attribute.value) {
      form.name = attribute.value.name || '';
      form.data_type = attribute.value.data_type || 'text';
      form.product_category_id = attribute.value.product_category_id || null;
    }
  } catch (error) {
    console.error('Error loading attribute:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Attribute not found';
    } else {
      errorMessage.value = 'Failed to load attribute';
    }
  }
};

const handleSubmit = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await attributesStore.updateItem(attributeId, form);
    successMessage.value = 'Attribute updated successfully!';
    setTimeout(() => {
      router.push('/admin/attributes');
    }, 1500);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update attribute';
    }
  }
};

onMounted(async () => {
  try {
    await productCategoriesStore.fetchItems({ per_page: 100 });
    await loadAttribute();
  } catch (error) {
    console.error('Error loading form data:', error);
    errorMessage.value = 'Failed to load form data';
  }
});
</script>
