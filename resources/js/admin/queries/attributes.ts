import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Attribute, PaginationMeta } from '../types/index';

export type AttributeListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  product_category_id?: string | number;
  group_id?: string | number;
  sort?: string;
  dir?: string;
};

const attributeKeys = {
  all: ['attributes'] as const,
  list: (params: AttributeListParams) => ['attributes', 'list', params] as const,
  detail: (id: number | string) => ['attributes', 'detail', id] as const,
};

const fetchAttributes = async (params: AttributeListParams) => {
  const response = await adminApi.attributes.index(params);
  const data: any = response.data;
  const items: Attribute[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useAttributeListQuery = (
  params: AttributeListParams | Ref<AttributeListParams>,
  options?: { enabled?: Ref<boolean> | boolean }
) => {
  const paramsRef = computed(() => unref(params));
  const enabled = computed(() => {
    if (options?.enabled === undefined) return true;
    return unref(options.enabled);
  });
  return useQuery({
    queryKey: computed(() => attributeKeys.list(paramsRef.value)),
    queryFn: () => fetchAttributes(paramsRef.value),
    placeholderData: keepPreviousData,
    enabled,
  });
};

export const useAttributeDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? attributeKeys.detail(idRef.value) : ['attributes', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Attribute id is required');
      const response = await adminApi.attributes.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useAttributeCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Attribute>) => adminApi.attributes.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: attributeKeys.all });
    },
  });
};

export const useAttributeUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Attribute> }) =>
      adminApi.attributes.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: attributeKeys.all });
      queryClient.invalidateQueries({ queryKey: attributeKeys.detail(variables.id) });
    },
  });
};

export const useAttributeDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.attributes.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: attributeKeys.all });
    },
  });
};


