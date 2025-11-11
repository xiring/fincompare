<?php

namespace Src\Auth\Application\DTOs;

/**
 * AdminUserDTO data transfer object.
 */
class AdminUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public array $roles = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            roles: $data['roles'] ?? [],
        );
    }
}
