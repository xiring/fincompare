import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Form, PaginationMeta } from '../types/index';

export type AdminFormListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  sort?: string;
  dir?: string;
};

const formKeys = {
  all: ['forms'] as const,
  list: (params: AdminFormListParams) => ['forms', 'list', params] as const,
  detail: (id: number | string) => ['forms', 'detail', id] as const,
};

const fetchForms = async (params: AdminFormListParams) => {
  const response = await adminApi.forms.index(params);
  const data: any = response.data;
  const items: Form[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useFormListQuery = (params: AdminFormListParams | Ref<AdminFormListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => formKeys.list(paramsRef.value)),
    queryFn: () => fetchForms(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useFormDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? formKeys.detail(idRef.value) : ['forms', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Form id is required');
      const response = await adminApi.forms.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useFormCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Form>) => adminApi.forms.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: formKeys.all });
    },
  });
};

export const useFormUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Form> }) =>
      adminApi.forms.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: formKeys.all });
      queryClient.invalidateQueries({ queryKey: formKeys.detail(variables.id) });
    },
  });
};

export const useFormDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.forms.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: formKeys.all });
    },
  });
};

export const useFormDuplicateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.forms.duplicate(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: formKeys.all });
    },
  });
};


