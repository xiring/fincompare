<?php
namespace Src\Shared\Application\DTOs;

class ContactMessageDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $message,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            email: (string) $data['email'],
            message: (string) $data['message'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ];
    }
}


