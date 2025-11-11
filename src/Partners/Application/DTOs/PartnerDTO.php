<?php

namespace Src\Partners\Application\DTOs;

/**
 * PartnerDTO data transfer object.
 */
class PartnerDTO
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $logo_path = null,
        public ?string $website_url = null,
        public ?string $contact_email = null,
        public ?string $contact_phone = null,
        public string $status = 'active',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            logo_path: $data['logo_path'] ?? null,
            website_url: $data['website_url'] ?? null,
            contact_email: $data['contact_email'] ?? null,
            contact_phone: $data['contact_phone'] ?? null,
            status: $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'logo_path' => $this->logo_path,
            'website_url' => $this->website_url,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'status' => $this->status,
        ];
    }
}
