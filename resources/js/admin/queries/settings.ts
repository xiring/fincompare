import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
import { adminApi } from '../services/api';

const settingsKeys = {
  all: ['settings'] as const,
};

const fetchSettings = async () => {
  const response = await adminApi.settings.show();
  const data: any = response.data;
  return (data as any)?.data || data;
};

export const useSettingsQuery = () =>
  useQuery({
    queryKey: settingsKeys.all,
    queryFn: fetchSettings,
  });

export const useSettingsUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Record<string, any>) => adminApi.settings.update(payload),
    onSuccess: (response) => {
      const data: any = (response.data as any)?.data || response.data;
      queryClient.setQueryData(settingsKeys.all, data);
    },
  });
};


