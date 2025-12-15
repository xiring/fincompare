<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit Group' : 'Create Group'" :description="isEdit ? 'Update group' : 'Add a new group'" />

    <LoadingSpinner v-if="isEdit && loading && !group" text="Loading group..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || group">
      <form @submit.prevent="handleSubmit" class="space-y-6">
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
          :hint="isEdit ? undefined : 'Leave empty to auto-generate from name'"
          :error="getError(errors, 'slug')"
        />

        <FormTextarea
          id="description"
          v-model="form.description"
          label="Description"
          :rows="3"
          :error="getError(errors, 'description')"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
          <FormInput
            id="sort_order"
            v-model.number="form.sort_order"
            label="Sort Order"
            type="number"
            min="0"
            :error="getError(errors, 'sort_order')"
          />

          <FormCheckbox
            id="is_active"
            v-model="form.is_active"
            label="Active"
            :options="[{ id: true, name: 'Active' }]"
            :error="getError(errors, 'is_active')"
          />
        </div>

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update Group' : 'Save Group'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/groups"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormCheckbox from '../../components/FormCheckbox.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import { useGroupCreateMutation, useGroupDetailQuery, useGroupUpdateMutation } from '../../queries/groups';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const groupId = route.params.id as string | undefined;

const isEdit = computed(() => !!groupId);
const {
  data: group,
  isLoading: detailLoading,
  error: detailError,
} = useGroupDetailQuery(computed(() => (isEdit.value ? groupId : undefined)));
const createMutation = useGroupCreateMutation();
const updateMutation = useGroupUpdateMutation();
const loading = computed(() => {
  if (isEdit.value) return detailLoading.value || updateMutation.isPending.value;
  return createMutation.isPending.value;
});

interface FormData {
  name: string;
  slug: string;
  description: string;
  is_active: boolean;
  sort_order: number;
}

const form = reactive<FormData>({
  name: '',
  slug: '',
  description: '',
  is_active: true,
  sort_order: 0,
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');
const detailErrorMessage = computed(() => {
  if (!detailError.value) return '';
  const err = detailError.value as any;
  return err?.response?.data?.message || 'Failed to load group';
});

watchEffect(() => {
  if (group.value) {
    const g: any = group.value;
    form.name = g.name || '';
    form.slug = g.slug || '';
    form.description = g.description || '';
    form.is_active = g.is_active ?? true;
    form.sort_order = g.sort_order ?? 0;
  }
  if (detailErrorMessage.value) {
    errorMessage.value = detailErrorMessage.value;
  }
});

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  const payload: any = {
    name: form.name,
    slug: form.slug,
    description: form.description,
    is_active: form.is_active,
    sort_order: form.sort_order,
  };

  try {
    if (!isEdit.value && !payload.slug && payload.name) {
      payload.slug = payload.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }

    if (isEdit.value && groupId) {
      await updateMutation.mutateAsync({ id: groupId, payload });
      successMessage.value = 'Group updated successfully!';
    } else {
      await createMutation.mutateAsync(payload);
      successMessage.value = 'Group created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/groups');
    }, 1200);
  } catch (error: unknown) {
    const err = error as any;
    if (err?.response?.status === 422) {
      errors.value = extractValidationErrors(err);
    } else {
      errorMessage.value = err?.response?.data?.message || (isEdit.value ? 'Failed to update group' : 'Failed to create group');
    }
  }
};

onMounted(() => {
  // detail query auto-loads via hook
});
</script>


