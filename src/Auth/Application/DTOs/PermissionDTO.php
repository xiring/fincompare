<?php
namespace Src\Auth\Application\DTOs;

/**
 * PermissionDTO data transfer object.
 *
 * @package Src\Auth\Application\DTOs
 */
class PermissionDTO
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(name: $data['name']);
    }
}


