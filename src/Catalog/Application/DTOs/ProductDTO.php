<?php
namespace Src\Catalog\Application\DTOs;

class ProductDTO
{
    public function __construct(
        public int $partner_id,
        public int $product_category_id,
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public bool $is_featured = false,
        public string $status = 'active',
        public array $attributes = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            partner_id: (int)$data['partner_id'],
            product_category_id: (int)$data['product_category_id'],
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            is_featured: (bool)($data['is_featured'] ?? false),
            status: $data['status'] ?? 'active',
            attributes: $data['attributes'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'partner_id' => $this->partner_id,
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'attributes' => $this->attributes,
        ];
    }
}


