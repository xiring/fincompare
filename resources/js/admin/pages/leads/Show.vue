<template>
  <div>
    <div class="mb-6">
      <router-link
        to="/admin/leads"
        class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 mb-4"
      >
        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Leads
      </router-link>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lead Details</h1>
    </div>

    <LoadingSpinner v-if="loading && !lead" text="Loading lead..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="lead" class="space-y-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lead Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ lead.full_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ lead.email || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobile Number</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ lead.mobile_number || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select
              v-model="form.status"
              @change="handleStatusUpdate"
              class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="new">New</option>
              <option value="contacted">Contacted</option>
              <option value="qualified">Qualified</option>
              <option value="converted">Converted</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Source</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ lead.source || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ new Date(lead.created_at).toLocaleString() }}</p>
          </div>
          <div v-if="lead.product_category">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Category</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ lead.product_category.name }}</p>
          </div>
          <div v-if="lead.product">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product</label>
            <p class="text-sm text-gray-900 dark:text-white">{{ lead.product.name }}</p>
          </div>
        </div>
        <div v-if="lead.message" class="mt-6">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
          <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ lead.message }}</p>
        </div>
        <div v-if="lead.meta && Object.keys(lead.meta).length > 0" class="mt-6">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Information</label>
          <pre class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 p-4 rounded-lg overflow-auto">{{ JSON.stringify(lead.meta, null, 2) }}</pre>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { adminApi } from '../../services/api';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ErrorMessage from '../../components/ErrorMessage.vue';
import SuccessMessage from '../../components/SuccessMessage.vue';

const route = useRoute();
const leadId = route.params.id;

const lead = ref(null);
const form = reactive({
  status: ''
});

const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);

const loadLead = async () => {
  loading.value = true;
  try {
    const response = await adminApi.leads.show(leadId);
    lead.value = response.data;
    form.status = lead.value.status || 'new';
  } catch (error) {
    console.error('Error loading lead:', error);
    if (error.response?.status === 404) {
      errorMessage.value = 'Lead not found';
    } else {
      errorMessage.value = 'Failed to load lead';
    }
  } finally {
    loading.value = false;
  }
};

const handleStatusUpdate = async () => {
  try {
    await adminApi.leads.update(leadId, { status: form.status });
    successMessage.value = 'Status updated successfully!';
    lead.value.status = form.status;
    setTimeout(() => {
      successMessage.value = '';
    }, 3000);
  } catch (error) {
    console.error('Error updating lead status:', error);
    errorMessage.value = 'Failed to update status';
    setTimeout(() => {
      errorMessage.value = '';
    }, 3000);
  }
};

onMounted(() => {
  loadLead();
});
</script>

