<template>
  <GuestLayout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
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
        class="w-full h-64 object-cover rounded-xl mb-6"
      />
      <div class="prose max-w-none" v-html="post.content"></div>
    </article>

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

onMounted(async () => {
  const slug = route.params.slug;

  try {
    const response = await apiService.getBlogPost(slug);
    post.value = response.data;
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Failed to load blog post';
    console.error('Failed to fetch blog post:', err);
  } finally {
    loading.value = false;
  }
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
