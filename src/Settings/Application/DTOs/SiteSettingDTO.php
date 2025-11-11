<?php
namespace Src\Settings\Application\DTOs;

/**
 * SiteSettingDTO data transfer object.
 *
 * @package Src\Settings\Application\DTOs
 */
class SiteSettingDTO
{
    public function __construct(
        public string $site_name = '',
        public ?string $site_slogon = null,
        public ?string $favicon = null,
        public ?string $logo = null,
        public ?string $seo_titl = null,
        public ?string $seo_keyword = null,
        public ?string $seo_description = null,
        public ?string $email_address = null,
        public ?string $contact_number = null,
        public ?string $address = null,
        public ?string $map_url = null,
        public ?string $facebook_url = null,
        public ?string $instgram_url = null,
        public ?string $twitter_url = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            site_name: (string)($data['site_name'] ?? ''),
            site_slogon: $data['site_slogon'] ?? null,
            favicon: $data['favicon'] ?? null,
            logo: $data['logo'] ?? null,
            seo_titl: $data['seo_titl'] ?? null,
            seo_keyword: $data['seo_keyword'] ?? null,
            seo_description: $data['seo_description'] ?? null,
            email_address: $data['email_address'] ?? null,
            contact_number: $data['contact_number'] ?? null,
            address: $data['address'] ?? null,
            map_url: $data['map_url'] ?? null,
            facebook_url: $data['facebook_url'] ?? null,
            instgram_url: $data['instgram_url'] ?? null,
            twitter_url: $data['twitter_url'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'site_name' => $this->site_name,
            'site_slogon' => $this->site_slogon,
            'seo_titl' => $this->seo_titl,
            'seo_keyword' => $this->seo_keyword,
            'seo_description' => $this->seo_description,
            'email_address' => $this->email_address,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'map_url' => $this->map_url,
            'facebook_url' => $this->facebook_url,
            'instgram_url' => $this->instgram_url,
            'twitter_url' => $this->twitter_url,
        ];

        // Only include logo/favicon when provided to avoid clearing existing files
        if ($this->logo !== null) {
            $data['logo'] = $this->logo;
        }
        if ($this->favicon !== null) {
            $data['favicon'] = $this->favicon;
        }

        return $data;
    }
}


