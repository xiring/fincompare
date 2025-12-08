<template>
  <GuestLayout>
    <HeroSection v-if="post" :title="post.title" :subtitle="`${post.category || TEXT.GENERAL} · ${formatDate(post.created_at)}`">
      <template #breadcrumb>
        <router-link to="/blog" class="text-white/90 hover:underline text-sm">{{ TEXT.BACK_TO_BLOG }}</router-link>
      </template>
    </HeroSection>

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
      <HeroSection :title="TEXT.POST_NOT_FOUND">
        <template #breadcrumb>
          <router-link to="/blog" class="text-white/90 hover:underline text-sm">{{ TEXT.BACK_TO_BLOG }}</router-link>
        </template>
      </HeroSection>
      <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <ErrorState
          :title="ERROR_MESSAGES.POST.LOAD"
          :message="error"
          back-url="/blog"
          :back-text="TEXT.BACK_TO_BLOG_TEXT"
          @retry="retryLoad"
        />
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

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { apiService } from '../../services/api';
import { useSEO, useErrorHandling } from '../../composables';
import { formatDate, getExcerpt, TEXT, ERROR_MESSAGES } from '../../utils';
import { ErrorState, HeroSection } from '../../components';
import GuestLayout from '../../layouts/GuestLayout.vue';
import type { BlogPost } from '../../types/index';

const route = useRoute();
const post = ref<BlogPost | null>(null);
const loading = ref<boolean>(true);
const { error, handleError, clearError } = useErrorHandling();

// SEO setup - will be updated when post loads
const getPostDescription = (): string => {
  if (!post.value) return '';
  return getExcerpt(post.value.content || '', 160);
};

const getPostKeywords = (): string[] => {
  if (!post.value) return [];
  const keywords: string[] = [];
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
  type: 'article',
});

// Update SEO when post loads
watch(
  post,
  (newPost) => {
    if (newPost) {
      useSEO({
        title: newPost.title || 'Blog Post',
        description: getPostDescription() || `Read our blog post: ${newPost.title}`,
        image: newPost.featured_image,
        keywords: getPostKeywords(),
        type: 'article',
      });
    }
  },
  { immediate: true }
);

const loadPost = async (): Promise<void> => {
  const slug = route.params.slug as string;

  try {
    const response = await apiService.getBlogPost(slug);
    post.value = response.data;
    clearError();
  } catch (err: any) {
    handleError(err, ERROR_MESSAGES.POST.LOAD_DETAIL);
  } finally {
    loading.value = false;
  }
};

const retryLoad = async (): Promise<void> => {
  clearError();
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
