import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { Permission, PaginationMeta } from '../types/index';

export type PermissionListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  sort?: string;
  dir?: string;
};

const permissionKeys = {
  all: ['permissions'] as const,
  list: (params: PermissionListParams) => ['permissions', 'list', params] as const,
  detail: (id: number | string) => ['permissions', 'detail', id] as const,
};

const fetchPermissions = async (params: PermissionListParams) => {
  const response = await adminApi.permissions.index(params);
  const data: any = response.data;
  const items: Permission[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const usePermissionListQuery = (params: PermissionListParams | Ref<PermissionListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => permissionKeys.list(paramsRef.value)),
    queryFn: () => fetchPermissions(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const usePermissionDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? permissionKeys.detail(idRef.value) : ['permissions', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('Permission id is required');
      const response = await adminApi.permissions.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const usePermissionCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<Permission>) => adminApi.permissions.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: permissionKeys.all });
    },
  });
};

export const usePermissionUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<Permission> }) =>
      adminApi.permissions.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: permissionKeys.all });
      queryClient.invalidateQueries({ queryKey: permissionKeys.detail(variables.id) });
    },
  });
};

export const usePermissionDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.permissions.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: permissionKeys.all });
    },
  });
};


