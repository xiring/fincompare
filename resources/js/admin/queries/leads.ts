import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Lead, PaginationMeta } from '../types/index';

export type LeadListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  status?: string;
  sort?: string;
  dir?: string;
};

const leadKeys = {
  all: ['leads'] as const,
  list: (params: LeadListParams) => ['leads', 'list', params] as const,
  detail: (id: number | string) => ['leads', 'detail', id] as const,
};

const fetchLeads = async (params: LeadListParams) => {
  const response = await adminApi.leads.index(params);
  const data: any = response.data;
  const items: Lead[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useLeadListQuery = (params: LeadListParams | Ref<LeadListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => leadKeys.list(paramsRef.value)),
    queryFn: () => fetchLeads(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useLeadDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? leadKeys.detail(idRef.value) : ['leads', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Lead id is required');
      const response = await adminApi.leads.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useLeadUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Lead> }) =>
      adminApi.leads.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: leadKeys.all });
      queryClient.invalidateQueries({ queryKey: leadKeys.detail(variables.id) });
    },
  });
};

export const useLeadExportMutation = () => {
  return useMutation({
    mutationFn: () => adminApi.leads.export(),
  });
};


