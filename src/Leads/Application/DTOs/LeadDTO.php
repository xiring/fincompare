<?php
namespace Src\Leads\Application\DTOs;

/**
 * LeadDTO data transfer object.
 *
 * @package Src\Leads\Application\DTOs
 */
class LeadDTO
{
    public function __construct(
        public ?int $product_category_id = null,
        public ?int $product_id = null,
        public ?string $full_name = null,
        public ?string $email = null,
        public ?string $mobile_number = null,
        public ?string $message = null,
        public ?string $status = null,
        public ?string $source = null,
        public array $meta = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_category_id: isset($data['product_category_id']) ? (int)$data['product_category_id'] : null,
            product_id: isset($data['product_id']) ? (int)$data['product_id'] : null,
            full_name: $data['full_name'] ?? null,
            email: $data['email'] ?? null,
            mobile_number: $data['mobile_number'] ?? null,
            message: $data['message'] ?? null,
            status: $data['status'] ?? null,
            source: $data['source'] ?? null,
            meta: $data['meta'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'product_category_id' => $this->product_category_id,
            'product_id' => $this->product_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'message' => $this->message,
            'status' => $this->status,
            'source' => $this->source,
            'meta' => $this->meta,
        ];
    }
}


