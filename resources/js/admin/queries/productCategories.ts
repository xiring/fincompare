import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { ProductCategory, PaginationMeta } from '../types/index';

export type ProductCategoryListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  group_id?: string | number;
  sort?: string;
  dir?: string;
};

const categoryKeys = {
  all: ['productCategories'] as const,
  list: (params: ProductCategoryListParams) => ['productCategories', 'list', params] as const,
  detail: (id: number | string) => ['productCategories', 'detail', id] as const,
};

const fetchCategories = async (params: ProductCategoryListParams) => {
  const response = await adminApi.productCategories.index(params);
  const data: any = response.data;
  const items: ProductCategory[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useProductCategoryListQuery = (params: ProductCategoryListParams | Ref<ProductCategoryListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => categoryKeys.list(paramsRef.value)),
    queryFn: () => fetchCategories(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useProductCategoryDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? categoryKeys.detail(idRef.value) : ['productCategories', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Category id is required');
      const response = await adminApi.productCategories.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useProductCategoryCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<ProductCategory>) => adminApi.productCategories.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: categoryKeys.all });
    },
  });
};

export const useProductCategoryUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<ProductCategory> }) =>
      adminApi.productCategories.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: categoryKeys.all });
      queryClient.invalidateQueries({ queryKey: categoryKeys.detail(variables.id) });
    },
  });
};

export const useProductCategoryDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.productCategories.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: categoryKeys.all });
    },
  });
};


