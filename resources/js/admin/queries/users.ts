import { useQuery, useMutation, useQueryClient, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { User, PaginationMeta } from '../types/index';

export type UserListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  role_id?: string | number;
  sort?: string;
  dir?: string;
};

const userKeys = {
  all: ['users'] as const,
  list: (params: UserListParams) => ['users', 'list', params] as const,
  detail: (id: number | string) => ['users', 'detail', id] as const,
};

const fetchUsers = async (params: UserListParams) => {
  const response = await adminApi.users.index(params);
  const data: any = response.data;
  const items: User[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useUserListQuery = (params: UserListParams | Ref<UserListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => userKeys.list(paramsRef.value)),
    queryFn: () => fetchUsers(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};

export const useUserDetailQuery = (id: number | string | Ref<number | string | undefined>) => {
  const idRef = computed(() => unref(id));
  return useQuery({
    queryKey: computed(() => (idRef.value ? userKeys.detail(idRef.value) : ['users', 'detail', 'missing'])),
    queryFn: async () => {
      if (!idRef.value) throw new Error('User id is required');
      const response = await adminApi.users.show(idRef.value);
      const data: any = response.data;
      return (data as any)?.data || data;
    },
    enabled: computed(() => !!idRef.value),
  });
};

export const useUserCreateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<User>) => adminApi.users.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: userKeys.all });
    },
  });
};

export const useUserUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (input: { id: number | string; payload: Partial<User> }) =>
      adminApi.users.update(input.id, input.payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: userKeys.all });
      queryClient.invalidateQueries({ queryKey: userKeys.detail(variables.id) });
    },
  });
};

export const useUserDeleteMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number | string) => adminApi.users.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: userKeys.all });
    },
  });
};


