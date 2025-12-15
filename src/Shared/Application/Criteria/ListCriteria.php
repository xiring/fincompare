<?php

namespace Src\Shared\Application\Criteria;

/**
 * Lightweight criteria object for list/search endpoints.
 */
class ListCriteria
{
    public function __construct(
        private readonly ?string $search = null,
        private readonly ?string $sort = null,
        private readonly string $dir = 'desc',
        private readonly ?int $perPage = null,
        private readonly array $filters = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            sort: $data['sort'] ?? null,
            dir: strtolower($data['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc',
            perPage: isset($data['per_page']) ? (int) $data['per_page'] : null,
            filters: $data['filters'] ?? [],
        );
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function getDir(): string
    {
        return $this->dir;
    }

    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    public function filters(): array
    {
        return $this->filters;
    }
}


