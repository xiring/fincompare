<?php

namespace Src\Catalog\Application\DTOs;

/**
 * ProductListFiltersDTO - Data Transfer Object for product listing filters.
 */
readonly class ProductListFiltersDTO
{
    public function __construct(
        public ?string $searchQuery = null,
        public ?string $categorySlug = null,
        public ?int $categoryId = null,
        public ?int $partnerId = null,
        public bool $featured = false,
        public int $perPage = 12
    ) {}

    /**
     * Create from request array.
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            searchQuery: $data['q'] ?? null,
            categorySlug: $data['category'] ?? null,
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
            partnerId: isset($data['partner_id']) ? (int) $data['partner_id'] : null,
            featured: filter_var($data['featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
            perPage: isset($data['per_page']) ? (int) $data['per_page'] : 12
        );
    }
}

