<?php

namespace Src\Shared\Presentation\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;

class DashboardStatsController extends Controller
{
    public function __construct(
        private AdminProductRepositoryInterface $productRepository,
        private LeadRepositoryInterface $leadRepository,
        private PartnerRepositoryInterface $partnerRepository,
        private AdminUserRepositoryInterface $userRepository
    ) {}

    /**
     * Get dashboard statistics
     * Uses paginate with per_page: 1 to efficiently get total counts
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Use paginate with per_page: 1 to get total counts efficiently
            // This is faster than count() queries and reuses existing repository methods
            $productsPaginator = $this->productRepository->paginate([], 1);
            $leadsPaginator = $this->leadRepository->paginate([], 1);
            $partnersPaginator = $this->partnerRepository->paginate([], 1);
            $usersPaginator = $this->userRepository->paginate([], 1);

            $stats = [
                'products' => $productsPaginator->total(),
                'leads' => $leadsPaginator->total(),
                'partners' => $partnersPaginator->total(),
                'users' => $usersPaginator->total(),
            ];

            return response()->json([
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

