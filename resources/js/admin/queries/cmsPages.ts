import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { CmsPage, PaginationMeta } from '../types/index';

export type CmsPageListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  status?: string;
  sort?: string;
  dir?: string;
};

const cmsPageKeys = {
  all: ['cmsPages'] as const,
  list: (params: CmsPageListParams) => ['cmsPages', 'list', params] as const,
  detail: (id: number | string) => ['cmsPages', 'detail', id] as const,
};

const fetchCmsPages = async (params: CmsPageListParams) => {
  const response = await adminApi.cmsPages.index(params);
  const data: any = response.data;
  const items: CmsPage[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useCmsPageListQuery = (params: CmsPageListParams | Ref<CmsPageListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => cmsPageKeys.list(paramsRef.value)),
    queryFn: () => fetchCmsPages(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useCmsPageDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? cmsPageKeys.detail(idRef.value) : ['cmsPages', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('CMS page id is required');
      const response = await adminApi.cmsPages.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useCmsPageCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<CmsPage>) => adminApi.cmsPages.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: cmsPageKeys.all });
    },
  });
};

export const useCmsPageUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<CmsPage> }) =>
      adminApi.cmsPages.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: cmsPageKeys.all });
      queryClient.invalidateQueries({ queryKey: cmsPageKeys.detail(variables.id) });
    },
  });
};

export const useCmsPageDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.cmsPages.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: cmsPageKeys.all });
    },
  });
};


