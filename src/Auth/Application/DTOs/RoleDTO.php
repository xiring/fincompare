<?php

namespace Src\Auth\Application\DTOs;

/**
 * RoleDTO data transfer object.
 */
class RoleDTO
{
    public function __construct(
        public string $name,
        public array $permissions = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            permissions: $data['permissions'] ?? [],
        );
    }
}
