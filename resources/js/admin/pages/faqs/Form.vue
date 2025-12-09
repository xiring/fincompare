<template>
  <div>
    <PageHeader :title="isEdit ? 'Edit FAQ' : 'Create FAQ'" :description="isEdit ? 'Update frequently asked question' : 'Add a new frequently asked question'" />

    <LoadingSpinner v-if="isEdit && loading && !faq" text="Loading FAQ..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="!isEdit || faq">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="question"
          v-model="form.question"
          label="Question"
          type="text"
          required
          :error="getError(errors, 'question')"
        />

        <FormTextarea
          id="answer"
          v-model="form.answer"
          label="Answer"
          :rows="6"
          required
          :error="getError(errors, 'answer')"
        />

        <FormActions
          :loading="loading"
          :submit-text="isEdit ? 'Update FAQ' : 'Save FAQ'"
          :loading-text="isEdit ? 'Updating...' : 'Creating...'"
          cancel-route="/admin/faqs"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useFaqsStore } from '../../stores';
import { extractValidationErrors, getError } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormActions from '../../components/FormActions.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const route = useRoute();
const router = useRouter();
const faqId = route.params.id as string | undefined;

const faqsStore = useFaqsStore();
const isEdit = computed(() => !!faqId);
const loading = computed(() => faqsStore.loading);
const faq = computed(() => faqsStore.currentItem);

interface FormData {
  question: string;
  answer: string;
}

const form = reactive<FormData>({
  question: '',
  answer: '',
});

const errors = ref<FormErrors>({});
const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const loadFaq = async (): Promise<void> => {
  if (!faqId) return;

  try {
    await faqsStore.fetchItem(faqId);
    if (faq.value) {
      form.question = faq.value.question || '';
      form.answer = faq.value.answer || '';
    }
  } catch (error: any) {
    console.error('Error loading FAQ:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'FAQ not found';
    } else {
      errorMessage.value = 'Failed to load FAQ';
    }
  }
};

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    if (isEdit.value && faqId) {
      await faqsStore.updateItem(faqId, form);
      successMessage.value = 'FAQ updated successfully!';
    } else {
      await faqsStore.createItem(form);
      successMessage.value = 'FAQ created successfully!';
    }

    setTimeout(() => {
      router.push('/admin/faqs');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || (isEdit.value ? 'Failed to update FAQ' : 'Failed to create FAQ');
    }
  }
};

onMounted(() => {
  if (isEdit.value) {
    loadFaq();
  }
});
</script>

