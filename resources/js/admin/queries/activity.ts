import { useQuery, keepPreviousData } from '@tanstack/vue-query';
import { computed, unref, type Ref } from 'vue';
import { adminApi } from '../services/api';
import { extractPagination } from '../stores/utils/pagination';
import type { ActivityLog, PaginationMeta } from '../types/index';

export type ActivityListParams = {
  page?: number;
  per_page?: number;
  q?: string;
  log_name?: string;
  sort?: string;
  dir?: string;
};

const activityKeys = {
  all: ['activity'] as const,
  list: (params: ActivityListParams) => ['activity', 'list', params] as const,
};

const fetchActivity = async (params: ActivityListParams) => {
  const response = await adminApi.activity.index(params);
  const data: any = response.data;
  const items: ActivityLog[] = data?.data ?? (Array.isArray(data) ? data : []);
  const pagination: PaginationMeta = extractPagination(data);
  return { items, pagination };
};

export const useActivityListQuery = (params: ActivityListParams | Ref<ActivityListParams>) => {
  const paramsRef = computed(() => unref(params));
  return useQuery({
    queryKey: computed(() => activityKeys.list(paramsRef.value)),
    queryFn: () => fetchActivity(paramsRef.value),
    placeholderData: keepPreviousData,
  });
};


