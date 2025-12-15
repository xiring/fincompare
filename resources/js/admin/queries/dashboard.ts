import { useQuery, keepPreviousData } from '@tanstack/vue-query';
import { adminApi } from '../services/api';
import type { Product, Lead, ActivityLog, DashboardStats } from '../types/index';

const dashboardKeys = {
  stats: ['dashboard', 'stats'] as const,
  recentProducts: ['dashboard', 'recentProducts'] as const,
  recentLeads: ['dashboard', 'recentLeads'] as const,
  recentActivity: ['dashboard', 'recentActivity'] as const,
};

type DashboardCounts = { products: number; leads: number; partners: number; users: number };

export const useDashboardStatsQuery = () =>
  useQuery({
    queryKey: dashboardKeys.stats,
    queryFn: async (): Promise<DashboardCounts> => {
      const response = await adminApi.stats.index();
      const data = ((response.data as any)?.data || response.data) as DashboardStats | DashboardCounts;
      return {
        products: (data as any).products ?? (data as any).total_products ?? 0,
        leads: (data as any).leads ?? (data as any).total_leads ?? 0,
        partners: (data as any).partners ?? (data as any).total_partners ?? 0,
        users: (data as any).users ?? (data as any).total_users ?? 0,
      };
    },
  });

export const useRecentProductsQuery = () =>
  useQuery({
    queryKey: dashboardKeys.recentProducts,
    queryFn: async () => {
      const response = await adminApi.products.index({ per_page: 5, sort: 'created_at', dir: 'desc' });
      const data: any = response.data;
      const items: Product[] = data?.data ?? (Array.isArray(data) ? data : []);
      return items.slice(0, 5);
    },
    placeholderData: keepPreviousData,
  });

export const useRecentLeadsQuery = () =>
  useQuery({
    queryKey: dashboardKeys.recentLeads,
    queryFn: async () => {
      const response = await adminApi.leads.index({ per_page: 50, status: 'new' });
      const data: any = response.data;
      const items: Lead[] = data?.data ?? (Array.isArray(data) ? data : []);
      return items.filter((lead) => (lead.status || 'new').toLowerCase() === 'new').slice(0, 5);
    },
    placeholderData: keepPreviousData,
  });

export const useRecentActivityQuery = () =>
  useQuery({
    queryKey: dashboardKeys.recentActivity,
    queryFn: async () => {
      const response = await adminApi.activity.index({ per_page: 5, sort: 'id', dir: 'desc' });
      const data: any = response.data;
      const items: ActivityLog[] = data?.data ?? (Array.isArray(data) ? data : []);
      return items.slice(0, 5);
    },
    placeholderData: keepPreviousData,
  });


