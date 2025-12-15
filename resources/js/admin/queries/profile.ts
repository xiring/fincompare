import { useQuery, useMutation, useQueryClient } from '@tanstack/vue-query';
import { adminApi } from '../services/api';
import type { User } from '../types/index';

const profileKeys = {
  me: ['profile', 'me'] as const,
};

export const useProfileQuery = () =>
  useQuery({
    queryKey: profileKeys.me,
    queryFn: async () => {
      const response = await adminApi.profile.show();
      const data: any = response.data;
      return (data as any)?.data || data;
    },
  });

export const useProfileUpdateMutation = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (payload: Partial<User>) => adminApi.profile.update(payload),
    onSuccess: (response) => {
      const data: any = (response as any)?.data?.data || (response as any)?.data;
      if (data) {
        queryClient.setQueryData(profileKeys.me, data);
      } else {
        queryClient.invalidateQueries({ queryKey: profileKeys.me });
      }
    },
  });
};

export const usePasswordUpdateMutation = () =>
  useMutation({
    mutationFn: (payload: { current_password: string; password: string; password_confirmation: string }) =>
      adminApi.profile.updatePassword(payload),
  });


