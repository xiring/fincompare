<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <router-link to="/blog" class="text-white/90 hover:underline text-sm">← Back to Blog</router-link>
        <h1 v-if="post" class="mt-2 text-3xl font-extrabold tracking-tight">{{ post.title }}</h1>
        <div v-if="post" class="mt-1 text-white/80 text-sm">
          {{ post.category || 'General' }} · {{ formatDate(post.created_at) }}
        </div>
      </div>
    </section>

    <article v-if="post" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in-up">
      <img
        v-if="post.featured_image"
        :src="post.featured_image"
        :alt="post.title"
        loading="lazy"
        class="w-full h-64 object-cover rounded-2xl mb-6"
      />
      <div class="prose max-w-none" v-html="post.content"></div>
    </article>

    <!-- Error State -->
    <div v-else-if="error && !loading" class="w-full">
      <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <router-link to="/blog" class="text-white/90 hover:underline text-sm">← Back to Blog</router-link>
          <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Post Not Found</h1>
        </div>
      </section>
      <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white border rounded-2xl p-12 text-center">
          <div class="max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
              <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to load blog post</h3>
            <p class="text-sm text-gray-600 mb-6">{{ error }}</p>
            <div class="flex gap-3 justify-center">
              <button
                @click="retryLoad"
                type="button"
                class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white transition-all shadow-sm hover:shadow-md btn-brand-primary"
                style="color: #ffffff !important;"
              >
                <RefreshIcon class="w-5 h-5 mr-2" />
                Try Again
              </button>
              <router-link
                to="/blog"
                class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-gray-700 bg-white border border-gray-300 transition-all shadow-sm hover:shadow-md hover:bg-gray-50"
              >
                Back to Blog
              </router-link>
            </div>
          </div>
        </div>
      </article>
    </div>

    <!-- Loading Skeleton -->
    <div v-else-if="loading" class="w-full">
      <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div class="animate-pulse">
            <div class="h-4 bg-white/20 rounded w-24 mb-4"></div>
            <div class="h-8 bg-white/20 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-white/20 rounded w-1/2"></div>
          </div>
        </div>
      </section>
      <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="animate-pulse">
          <div class="h-64 bg-gray-200 rounded-xl mb-6"></div>
          <div class="space-y-4">
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
            <div class="h-4 bg-gray-200 rounded w-4/5"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
          </div>
        </div>
      </article>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { apiService } from '../../services/api';
import { useSEO } from '../../composables';
import { formatDate, getExcerpt } from '../../utils';
import { RefreshIcon } from '../../components/icons';
import GuestLayout from '../../layouts/GuestLayout.vue';

const route = useRoute();
const post = ref(null);
const loading = ref(true);
const error = ref(null);

// SEO setup - will be updated when post loads
const getPostDescription = () => {
  if (!post.value) return '';
  return getExcerpt(post.value.content || '', 160);
};

const getPostKeywords = () => {
  if (!post.value) return [];
  const keywords = [];
  if (post.value.category) keywords.push(post.value.category);
  if (post.value.tags && Array.isArray(post.value.tags)) {
    keywords.push(...post.value.tags);
  }
  return keywords;
};

// Initialize SEO with default values
useSEO({
  title: 'Blog Post',
  description: 'Read our latest blog post',
  type: 'article'
});

// Update SEO when post loads
watch(post, (newPost) => {
  if (newPost) {
    useSEO({
      title: newPost.title || 'Blog Post',
      description: getPostDescription() || `Read our blog post: ${newPost.title}`,
      image: newPost.featured_image,
      keywords: getPostKeywords(),
      type: 'article'
    });
  }
}, { immediate: true });

const loadPost = async () => {
  const slug = route.params.slug;

  try {
    const response = await apiService.getBlogPost(slug);
    post.value = response.data;
    error.value = null;
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Failed to load blog post';
    console.error('Failed to fetch blog post:', err);
  } finally {
    loading.value = false;
  }
};

const retryLoad = async () => {
  error.value = null;
  loading.value = true;
  await loadPost();
};

onMounted(() => {
  loadPost();
});
</script>

<style scoped>
.prose {
  @apply text-gray-700;
}

.prose :deep(h1),
.prose :deep(h2),
.prose :deep(h3),
.prose :deep(h4) {
  @apply font-semibold text-gray-900 mt-6 mb-4;
}

.prose :deep(p) {
  @apply mb-4;
}

.prose :deep(a) {
  @apply text-[color:var(--brand-primary)] no-underline hover:underline;
}

.prose :deep(ul),
.prose :deep(ol) {
  @apply mb-4 pl-6;
}

.prose :deep(li) {
  @apply mb-2;
}

.prose :deep(img) {
  @apply rounded-lg my-6;
}
</style>
