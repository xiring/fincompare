import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Faq, PaginationMeta } from '../types/index';

export type FaqListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  sort?: string;
  dir?: string;
};

const faqKeys = {
  all: ['faqs'] as const,
  list: (params: FaqListParams) => ['faqs', 'list', params] as const,
  detail: (id: number | string) => ['faqs', 'detail', id] as const,
};

const fetchFaqs = async (params: FaqListParams) => {
  const response = await adminApi.faqs.index(params);
  const data: any = response.data;
  const items: Faq[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useFaqListQuery = (params: FaqListParams | Ref<FaqListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => faqKeys.list(paramsRef.value)),
    queryFn: () => fetchFaqs(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useFaqDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? faqKeys.detail(idRef.value) : ['faqs', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('FAQ id is required');
      const response = await adminApi.faqs.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useFaqCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Faq>) => adminApi.faqs.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: faqKeys.all });
    },
  });
};

export const useFaqUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Faq> }) =>
      adminApi.faqs.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: faqKeys.all });
      queryClient.invalidateQueries({ queryKey: faqKeys.detail(variables.id) });
    },
  });
};

export const useDeleteFaqMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.faqs.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: faqKeys.all });
    },
  });
};


