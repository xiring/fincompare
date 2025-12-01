<?php

namespace Src\Forms\Application\DTOs;

/**
 * FormDTO data transfer object.
 */
class FormDTO
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public string $status = 'active',
        public string $type = 'pre_form',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            status: $data['status'] ?? 'active',
            type: $data['type'] ?? 'pre_form',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'type' => $this->type,
        ];
    }
}

