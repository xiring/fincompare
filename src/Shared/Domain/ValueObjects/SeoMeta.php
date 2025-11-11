<?php
namespace Src\Shared\Domain\ValueObjects;

/**
 * SeoMeta class.
 *
 * @package Src\Shared\Domain\ValueObjects
 */
class SeoMeta
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $keywords = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['seo_title'] ?? null,
            description: $data['seo_description'] ?? null,
            keywords: $data['seo_keywords'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'seo_title' => $this->title,
            'seo_description' => $this->description,
            'seo_keywords' => $this->keywords,
        ];
    }
}


