<?php

namespace Src\Content\Application\DTOs;

/**
 * BlogListFiltersDTO - Data Transfer Object for blog listing filters.
 */
readonly class BlogListFiltersDTO
{
    public function __construct(
        public ?string $searchQuery = null,
        public ?string $category = null,
        public ?string $tag = null,
        public string $sort = 'desc',
        public int $perPage = 9
    ) {}

    /**
     * Create from request array.
     */
    public static function fromRequest(array $data): self
    {
        $sort = strtolower($data['sort'] ?? 'desc');
        $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'desc';

        return new self(
            searchQuery: $data['q'] ?? null,
            category: $data['category'] ?? null,
            tag: $data['tag'] ?? null,
            sort: $sort,
            perPage: isset($data['per_page']) ? (int) $data['per_page'] : 9
        );
    }
}

