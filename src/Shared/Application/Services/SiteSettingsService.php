<?php

namespace Src\Shared\Application\Services;

use Src\Shared\Application\DTOs\SiteSettingsResponseDTO;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;

/**
 * SiteSettingsService - Service for site settings operations.
 */
class SiteSettingsService
{
    public function __construct(
        private readonly SiteSettingRepositoryInterface $siteSettingsRepository
    ) {}

    /**
     * Get site settings for public API.
     */
    public function getPublicSettings(): SiteSettingsResponseDTO
    {
        $settings = $this->siteSettingsRepository->get();

        return new SiteSettingsResponseDTO(
            site_name: $settings->site_name ?? config('app.name', 'FinCompare'),
            site_slogon: $settings->site_slogon ?? null,
            favicon: $settings->favicon ? asset('storage/' . $settings->favicon) : null,
            logo: $settings->logo ? asset('storage/' . $settings->logo) : null,
            seo_title: $settings->seo_titl ?? null,
            seo_keywords: $settings->seo_keyword ?? null,
            seo_description: $settings->seo_description ?? 'Compare financial products and find the best deals.',
            email_address: $settings->email_address ?? null,
            contact_number: $settings->contact_number ?? null,
            address: $settings->address ?? null,
            map_url: $settings->map_url ?? null,
            facebook_url: $settings->facebook_url ?? null,
            instagram_url: $settings->instgram_url ?? null,
            twitter_url: $settings->twitter_url ?? null,
        );
    }
}

