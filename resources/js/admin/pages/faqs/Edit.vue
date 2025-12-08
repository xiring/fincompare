<template>
  <div>
    <PageHeader title="Edit FAQ" description="Update frequently asked question" />

    <LoadingSpinner v-if="loading && !faq" text="Loading FAQ..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard v-if="faq">
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
          submit-text="Update FAQ"
          loading-text="Updating..."
          cancel-route="/admin/faqs"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
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
const faqId = route.params.id as string;

const faqsStore = useFaqsStore();
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
const loading = computed(() => faqsStore.loading);

const loadFaq = async (): Promise<void> => {
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
    await faqsStore.updateItem(faqId, form);
    successMessage.value = 'FAQ updated successfully!';
    setTimeout(() => {
      router.push('/admin/faqs');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update FAQ';
    }
  }
};

onMounted(() => {
  loadFaq();
});
</script>
