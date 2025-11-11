<?php
namespace Src\Catalog\Application\DTOs;

/**
 * AttributeDTO data transfer object.
 *
 * @package Src\Catalog\Application\DTOs
 */
class AttributeDTO
{
    public function __construct(
        public int $product_category_id,
        public string $name,
        public ?string $slug = null,
        public string $data_type = 'text',
        public ?string $unit = null,
        public bool $is_filterable = false,
        public bool $is_required = false,
        public int $sort_order = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_category_id: (int)$data['product_category_id'],
            name: $data['name'],
            slug: $data['slug'] ?? null,
            data_type: $data['data_type'] ?? 'text',
            unit: $data['unit'] ?? null,
            is_filterable: (bool)($data['is_filterable'] ?? false),
            is_required: (bool)($data['is_required'] ?? false),
            sort_order: (int)($data['sort_order'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'data_type' => $this->data_type,
            'unit' => $this->unit,
            'is_filterable' => $this->is_filterable,
            'is_required' => $this->is_required,
            'sort_order' => $this->sort_order,
        ];
    }
}


