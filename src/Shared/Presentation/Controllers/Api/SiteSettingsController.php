<?php

namespace Src\Shared\Presentation\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Src\Shared\Application\Services\SiteSettingsService;

/**
 * SiteSettingsController - Handles site settings API endpoints.
 */
class SiteSettingsController extends Controller
{
    public function __construct(
        private readonly SiteSettingsService $siteSettingsService
    ) {}

    /**
     * Get public site settings.
     */
    public function index(): JsonResponse
    {
        $settings = $this->siteSettingsService->getPublicSettings();

        return response()->json($settings->toArray());
    }
}
