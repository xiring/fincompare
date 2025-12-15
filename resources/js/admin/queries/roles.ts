import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Role, PaginationMeta } from '../types/index';

export type RoleListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  sort?: string;
  dir?: string;
};

const roleKeys = {
  all: ['roles'] as const,
  list: (params: RoleListParams) => ['roles', 'list', params] as const,
  detail: (id: number | string) => ['roles', 'detail', id] as const,
};

const fetchRoles = async (params: RoleListParams) => {
  const response = await adminApi.roles.index(params);
  const data: any = response.data;
  const items: Role[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useRoleListQuery = (params: RoleListParams | Ref<RoleListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => roleKeys.list(paramsRef.value)),
    queryFn: () => fetchRoles(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useRoleDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? roleKeys.detail(idRef.value) : ['roles', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Role id is required');
      const response = await adminApi.roles.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useRoleCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Role>) => adminApi.roles.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: roleKeys.all });
    },
  });
};

export const useRoleUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Role> }) =>
      adminApi.roles.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: roleKeys.all });
      queryClient.invalidateQueries({ queryKey: roleKeys.detail(variables.id) });
    },
  });
};

export const useRoleDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.roles.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: roleKeys.all });
    },
  });
};


