<template>
  <div>
    <div class="mb-6">
      <router-link
        to="/admin/leads"
        class="inline-flex items-center text-sm text-charcoal-600"
      >
        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Leads
      </router-link>
      <h1 class="text-2xl font-bold text-charcoal-800">Lead Details</h1>
    </div>

    <LoadingSpinner v-if="loading && !lead" text="Loading lead..." />
    <ErrorMessage v-else-if="errorMessage" :message="errorMessage" class="mb-6" />
    <SuccessMessage v-if="successMessage" :message="successMessage" class="mb-6" />

    <div v-if="lead" class="space-y-6">
      <FormCard>
        <h2 class="text-lg font-semibold text-charcoal-800">Lead Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-charcoal-700">Full Name</label>
            <p class="text-sm text-charcoal-800">{{ lead.full_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-charcoal-700">Email</label>
            <p class="text-sm text-charcoal-800">{{ lead.email || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-charcoal-700">Mobile Number</label>
            <p class="text-sm text-charcoal-800">{{ lead.mobile_number || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-charcoal-700">Status</label>
            <select
              v-model="form.status"
              @change="handleStatusUpdate"
              class="block w-full px-4 py-2 border border-charcoal-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900"
            >
              <option value="new">New</option>
              <option value="contacted">Contacted</option>
              <option value="qualified">Qualified</option>
              <option value="converted">Converted</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-charcoal-700">Source</label>
            <p class="text-sm text-charcoal-800">{{ lead.source || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-charcoal-700">Date</label>
            <p class="text-sm text-charcoal-800">{{ new Date(lead.created_at).toLocaleString() }}</p>
          </div>
          <div v-if="lead.product_category">
            <label class="block text-sm font-medium text-charcoal-700">Product Category</label>
            <p class="text-sm text-charcoal-800">{{ lead.product_category.name }}</p>
          </div>
          <div v-if="lead.product">
            <label class="block text-sm font-medium text-charcoal-700">Product</label>
            <p class="text-sm text-charcoal-800">{{ lead.product.name }}</p>
          </div>
        </div>
        <div v-if="lead.message" class="mt-6">
          <label class="block text-sm font-medium text-charcoal-700">Message</label>
          <p class="text-sm text-charcoal-800">{{ lead.message }}</p>
        </div>
        <div v-if="lead.meta && Object.keys(lead.meta).length > 0" class="mt-6">
          <label class="block text-sm font-medium text-charcoal-700">Additional Information</label>
          <pre class="text-sm text-charcoal-800">{{ JSON.stringify(lead.meta, null, 2) }}</pre>
        </div>
      </FormCard>
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
import FormCard from '../../components/FormCard.vue';

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

