<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8" style="background: linear-gradient(to bottom, #1a1f2e 0%, #1a1f2e 40%, #4a5568 40%, #4a5568 60%, #14b8a6 60%, #14b8a6 80%, #f7fafc 80%, #f7fafc 100%);">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 border border-charcoal-200">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <div class="mx-auto h-12 w-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
          <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-charcoal-800">Sign In</h2>
        <p class="mt-2 text-sm text-charcoal-600">Welcome back! Please login to your account</p>
      </div>

      <!-- Error Messages -->
      <div v-if="errors.email || errors.password || errorMessage" class="mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Authentication Error</h3>
              <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                  <li v-if="errors.email">{{ errors.email[0] }}</li>
                  <li v-if="errors.password">{{ errors.password[0] }}</li>
                  <li v-if="errorMessage">{{ errorMessage }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="space-y-6">
        <!-- Email Field -->
        <div>
          <label for="email" class="block text-sm font-medium text-charcoal-700 mb-2">
            Email Address
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
              </svg>
            </div>
            <input
              id="email"
              v-model="form.email"
              type="email"
              autocomplete="email"
              required
              class="block w-full pl-10 pr-3 py-3 border border-charcoal-300 rounded-lg shadow-sm placeholder-charcoal-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors"
              placeholder="Enter your email"
              :class="{ 'border-red-300': errors.email }"
            />
          </div>
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
        </div>

        <!-- Password Field -->
        <div>
          <label for="password" class="block text-sm font-medium text-charcoal-700 mb-2">
            Password
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-charcoal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="current-password"
              required
              class="block w-full pl-10 pr-10 py-3 border border-charcoal-300 rounded-lg shadow-sm placeholder-charcoal-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-charcoal-900 transition-colors"
              placeholder="Enter your password"
              :class="{ 'border-red-300': errors.password }"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-charcoal-400 hover:text-charcoal-600"
            >
              <svg v-if="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
              <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-charcoal-300 rounded"
            />
            <label for="remember" class="ml-2 block text-sm text-charcoal-700">
              Remember me
            </label>
          </div>
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ loading ? 'Signing in...' : 'Sign In' }}</span>
          </button>
        </div>
      </form>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();

const form = reactive({
  email: '',
  password: '',
  remember: false
});

const errors = ref({});
const errorMessage = ref('');
const successMessage = ref('');
const loading = ref(false);
const showPassword = ref(false);

// Check for session status or errors from Laravel
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('error')) {
    errorMessage.value = 'Invalid credentials. Please try again.';
  }
});

const handleLogin = async () => {
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';
  loading.value = true;

  try {
    const response = await axios.post('/login', {
      email: form.email,
      password: form.password,
      remember: form.remember
    });

    // If successful, redirect
    if (response.status === 200 || response.status === 204) {
      localStorage.setItem('authenticated', 'true');
      const redirect = route.query.redirect || '/admin';
      window.location.href = redirect;
    }
  } catch (error) {
    loading.value = false;

    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else if (error.response?.status === 401) {
      errorMessage.value = error.response.data.message || 'Invalid email or password.';
    } else {
      errorMessage.value = 'An error occurred. Please try again.';
    }
  }
};
</script>

<style scoped>
/* Additional custom styles if needed */
</style>
