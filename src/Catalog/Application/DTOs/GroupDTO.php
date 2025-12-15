<?php

namespace Src\Catalog\Application\DTOs;

/**
 * GroupDTO data transfer object.
 */
class GroupDTO
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public bool $is_active = true,
        public int $sort_order = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            is_active: (bool) ($data['is_active'] ?? true),
            sort_order: (int) ($data['sort_order'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];
    }
}


