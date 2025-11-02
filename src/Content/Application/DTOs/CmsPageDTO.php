<?php
namespace Src\Content\Application\DTOs;

use Src\Shared\Domain\ValueObjects\SeoMeta;

class CmsPageDTO
{
    public function __construct(
        public string $title = '',
        public ?string $slug = null,
        public ?string $content = null,
        public string $status = 'draft',
        public SeoMeta $seo = new SeoMeta(),
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: (string)($data['title'] ?? ''),
            slug: $data['slug'] ?? null,
            content: $data['content'] ?? null,
            status: (string)($data['status'] ?? 'draft'),
            seo: SeoMeta::fromArray($data),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
        ] + $this->seo->toArray();
    }
}


