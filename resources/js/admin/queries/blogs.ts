import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { BlogPost, PaginationMeta } from '../types/index';

export type BlogListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  status?: string;
  sort?: string;
  dir?: string;
};

const blogKeys = {
  all: ['blogs'] as const,
  list: (params: BlogListParams) => ['blogs', 'list', params] as const,
  detail: (id: number | string) => ['blogs', 'detail', id] as const,
};

const fetchBlogs = async (params: BlogListParams) => {
  const response = await adminApi.blogs.index(params);
  const data: any = response.data;
  const items: BlogPost[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useBlogListQuery = (params: BlogListParams | Ref<BlogListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => blogKeys.list(paramsRef.value)),
    queryFn: () => fetchBlogs(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useBlogDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? blogKeys.detail(idRef.value) : ['blogs', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Blog id is required');
      const response = await adminApi.blogs.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useBlogCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<BlogPost>) => adminApi.blogs.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: blogKeys.all });
    },
  });
};

export const useBlogUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<BlogPost> }) =>
      adminApi.blogs.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: blogKeys.all });
      queryClient.invalidateQueries({ queryKey: blogKeys.detail(variables.id) });
    },
  });
};

export const useBlogDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.blogs.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: blogKeys.all });
    },
  });
};


