<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Attribute' : 'Create Attribute'" :description="isEdit ? 'Update attribute information' : 'Add a new product attribute'" />

    <LoadingSpinner v-if="isEdit && loading && !attribute" text="Loading attribute..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || attribute">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="name"
          v-model="form.name"
          label="Name"
          type="text"
          required
          :error="getError(errors, 'name')"
        />

        <FormSelect
          id="data_type"
          v-model="form.data_type"
          label="Data Type"
          :options="typeOptions"
          required
          :error="getError(errors, 'data_type')"
        />

        <GroupSelect
          id="group_id"
          v-model="form.group_id"
          label="Group"
          placeholder="-- Select Group --"
          :error="getError(errors, 'group_id')"
        />

        <FormSelect
          id="product_category_id"
          v-model="form.product_category_id"
          label="Product Category"
          :options="categoryOptions"
          placeholder="All Categories"
          :error="getError(errors, 'product_category_id')"
        />
        <div v-if="form.product_category_id" class="text-sm text-charcoal-600">
          <GroupBadge
            v-if="categories.find((c: any) => c.id === form.product_category_id)?.group"
            :name="(categories.find((c: any) => c.id === form.product_category_id) as any)?.group?.name"
          />
        </div>

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update Attribute' : 'Save Attribute'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/attributes"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormSelect from '../../components/FormSelect.vue';
import GroupSelect from '../../components/GroupSelect.vue';
import GroupBadge from '../../components/GroupBadge.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { ConstantOptions } from '../../constants/ConstantOptions';
import {
  useAttributeCreateMutation,
  useAttributeDetailQuery,
  useAttributeUpdateMutation,
} from '../../queries/attributes';
import { useProductCategoryListQuery } from '../../queries/productCategories';
import { useGroupListQuery } from '../../queries/groups';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const attributeId = route.params.id as string | undefined;

const isEdit = computed(() => !!attributeId);
const {
  data: attribute,
  isLoading: detailLoading,
  error: detailError,
} = useAttributeDetailQuery(computed(() => (isEdit.value ? attributeId : undefined)));
const createMutation = useAttributeCreateMutation();
const updateMutation = useAttributeUpdateMutation();
const {
  data: categoriesData,
  isLoading: categoriesLoading,
  isFetching: categoriesFetching,
} = useProductCategoryListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const { data: groupsData } = useGroupListQuery({ per_page: 500, sort: 'name', dir: 'asc' });
const categories = computed(() => (categoriesData.value?.items || []) as any[]);
const groups = computed(() => (groupsData.value?.items || []) as any[]);

const loading = computed(() => {
  const catLoading = categoriesLoading.value || categoriesFetching.value;
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value || catLoading;
  return createMutation.isPending.value || catLoading;
});

interface FormData {
  name: string;
  data_type: 'text' | 'number' | 'percentage' | 'boolean' | 'json';
  product_category_id: number | null;
  group_id: number | null;
}

const form = reactive<FormData>({
  name: '',
  data_type: 'text',
  product_category_id: null,
  group_id: null,
});

const typeOptions = ConstantOptions.attributeDataTypes();

const filteredCategories = computed(() => {
  if (!form.group_id) return categories.value;
  return categories.value.filter((c: any) => c.group_id === form.group_id);
});
const categoryOptions = computed(() => {
  return [{ id: null, name: 'All Categories' }, ...filteredCategories.value];
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load attribute';
});

watchEffect(() => {
  if (attribute.value) {
    form.name = attribute.value.name || '';
    form.data_type = attribute.value.data_type || 'text';
    form.product_category_id = attribute.value.product_category_id || null;
    const matchedCategory = categories.value.find((c: any) => c.id === form.product_category_id);
    form.group_id = matchedCategory?.group_id ?? matchedCategory?.group?.id ?? null;
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
    const data = {
      ...form,
      product_category_id: form.product_category_id || undefined,
    };

    if (isEdit.value && attributeId) {
      await updateMutation.mutateAsync({ id: attributeId, payload: data });
      successMessage.value = 'Attribute updated successfully!';
    } else {
      await createMutation.mutateAsync(data);
      successMessage.value = 'Attribute created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/attributes');
    }, 1500);
  } catch (error: unknown) {
    const err = error as any;
    if (err?.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value =
        err?.response?.data?.message || (isEdit.value ? 'Failed to update attribute' : 'Failed to create attribute');
    }
  }
};
</script>

