import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Partner, PaginationMeta } from '../types/index';

export type PartnerListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  sort?: string;
  dir?: string;
};

const partnerKeys = {
  all: ['partners'] as const,
  list: (params: PartnerListParams) => ['partners', 'list', params] as const,
  detail: (id: number | string) => ['partners', 'detail', id] as const,
};

const fetchPartners = async (params: PartnerListParams) => {
  const response = await adminApi.partners.index(params);
  const data: any = response.data;
  const items: Partner[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const usePartnerListQuery = (params: PartnerListParams | Ref<PartnerListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => partnerKeys.list(paramsRef.value)),
    queryFn: () => fetchPartners(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const usePartnerDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? partnerKeys.detail(idRef.value) : ['partners', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Partner id is required');
      const response = await adminApi.partners.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const usePartnerCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Partner>) => adminApi.partners.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: partnerKeys.all });
    },
  });
};

export const usePartnerUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Partner> }) =>
      adminApi.partners.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: partnerKeys.all });
      queryClient.invalidateQueries({ queryKey: partnerKeys.detail(variables.id) });
    },
  });
};

export const usePartnerDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.partners.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: partnerKeys.all });
    },
  });
};


