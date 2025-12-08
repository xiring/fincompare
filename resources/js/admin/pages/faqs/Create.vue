<template>
  <div>
    <PageHeader title="Create FAQ" description="Add a new frequently asked question" />

    <ErrorMessage v-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <FormCard>
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <FormInput
          id="question"
          v-model="form.question"
          label="Question"
          type="text"
          required
          :error="errors.question"
        />

        <FormTextarea
          id="answer"
          v-model="form.answer"
          label="Answer"
          :rows="6"
          required
          :error="errors.answer"
        />

        <FormActions
          :loading="loading"
          submit-text="Save FAQ"
          loading-text="Creating..."
          cancel-route="/admin/faqs"
        />
      </form>
    </FormCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useFaqsStore } from '../../stores';
import { extractValidationErrors } from '../../utils/validation';
import PageHeader from '../../components/PageHeader.vue';
import FormCard from '../../components/FormCard.vue';
import FormInput from '../../components/FormInput.vue';
import FormTextarea from '../../components/FormTextarea.vue';
import FormActions from '../../components/FormActions.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';
import type { FormErrors } from '../../types/index';

const router = useRouter();
const faqsStore = useFaqsStore();

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

const handleSubmit = async (): Promise<void> => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    await faqsStore.createItem(form);
    successMessage.value = 'FAQ created successfully!';
    setTimeout(() => {
      router.push('/admin/faqs');
    }, 1500);
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = extractValidationErrors(error);
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create FAQ';
    }
  }
};
</script>
