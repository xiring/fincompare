import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Product, PaginationMeta } from '../types/index';

export type ProductListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  product_category_id?: string | number;
  partner_id?: string | number;
  sort?: string;
  dir?: string;
};

const productKeys = {
  all: ['products'] as const,
  list: (params: ProductListParams) => ['products', 'list', params] as const,
  detail: (id: number | string) => ['products', 'detail', id] as const,
};

const fetchProducts = async (params: ProductListParams) => {
  const response = await adminApi.products.index(params);
  const data: any = response.data;
  const items: Product[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useProductListQuery = (params: ProductListParams | Ref<ProductListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => productKeys.list(paramsRef.value)),
    queryFn: () => fetchProducts(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useProductDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? productKeys.detail(idRef.value) : ['products', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Product id is required');
      const response = await adminApi.products.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useProductCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Product>) => adminApi.products.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: productKeys.all });
    },
  });
};

export const useProductUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Product> }) =>
      adminApi.products.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: productKeys.all });
      queryClient.invalidateQueries({ queryKey: productKeys.detail(variables.id) });
    },
  });
};

export const useProductDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.products.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: productKeys.all });
    },
  });
};

export const useProductDuplicateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.products.duplicate(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: productKeys.all });
    },
  });
};

export const useProductImportMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { file: File; delimiter?: string; has_header?: boolean }) =>
      adminApi.products.import(input.file, input.delimiter, input.has_header),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: productKeys.all });
    },
  });
};


