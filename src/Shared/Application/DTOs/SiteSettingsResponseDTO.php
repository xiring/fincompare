<?php

namespace Src\Shared\Application\DTOs;

/**
 * SiteSettingsResponseDTO - Data Transfer Object for site settings API response.
 */
readonly class SiteSettingsResponseDTO
{
    public function __construct(
        public string $site_name,
        public ?string $site_slogon = null,
        public ?string $favicon = null,
        public ?string $logo = null,
        public ?string $seo_title = null,
        public ?string $seo_keywords = null,
        public string $seo_description = 'Compare financial products and find the best deals.',
        public ?string $email_address = null,
        public ?string $contact_number = null,
        public ?string $address = null,
        public ?string $map_url = null,
        public ?string $facebook_url = null,
        public ?string $instagram_url = null,
        public ?string $twitter_url = null,
    ) {}

    /**
     * Convert to array for JSON response.
     */
    public function toArray(): array
    {
        return [
            'site_name' => $this->site_name,
            'site_slogon' => $this->site_slogon,
            'favicon' => $this->favicon,
            'logo' => $this->logo,
            'seo_title' => $this->seo_title,
            'seo_keywords' => $this->seo_keywords,
            'seo_description' => $this->seo_description,
            'email_address' => $this->email_address,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'map_url' => $this->map_url,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url' => $this->twitter_url,
        ];
    }
}

