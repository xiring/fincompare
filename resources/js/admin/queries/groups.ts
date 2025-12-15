import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Group, PaginationMeta } from '../types/index';

export type GroupListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  sort?: string;
  dir?: string;
};

const groupKeys = {
  all: ['groups'] as const,
  list: (params: GroupListParams) => ['groups', 'list', params] as const,
  detail: (id: number | string) => ['groups', 'detail', id] as const,
};

const fetchGroups = async (params: GroupListParams) => {
  const response = await adminApi.groups.index(params);
  const data: any = response.data;
  const items: Group[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useGroupListQuery = (params: GroupListParams | Ref<GroupListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => groupKeys.list(paramsRef.value)),
    queryFn: () => fetchGroups(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useGroupDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? groupKeys.detail(idRef.value) : ['groups', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Group id is required');
      const response = await adminApi.groups.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useGroupCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Group>) => adminApi.groups.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: groupKeys.all });
    },
  });
};

export const useGroupUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Group> }) =>
      adminApi.groups.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: groupKeys.all });
      queryClient.invalidateQueries({ queryKey: groupKeys.detail(variables.id) });
    },
  });
};

export const useGroupDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.groups.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: groupKeys.all });
    },
  });
};


