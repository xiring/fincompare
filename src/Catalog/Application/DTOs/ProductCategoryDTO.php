<?php

namespace Src\Catalog\Application\DTOs;

/**
 * ProductCategoryDTO data transfer object.
 */
class ProductCategoryDTO
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public ?string $image = null,
        public bool $is_active = true,
        public ?int $pre_form_id = null,
        public ?int $post_form_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            is_active: (bool) ($data['is_active'] ?? true),
            pre_form_id: isset($data['pre_form_id']) && $data['pre_form_id'] ? (int) $data['pre_form_id'] : null,
            post_form_id: isset($data['post_form_id']) && $data['post_form_id'] ? (int) $data['post_form_id'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'pre_form_id' => $this->pre_form_id,
            'post_form_id' => $this->post_form_id,
        ];
    }
}
