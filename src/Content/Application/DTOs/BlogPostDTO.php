<?php
namespace Src\Content\Application\DTOs;

use Src\Shared\Domain\ValueObjects\SeoMeta;

class BlogPostDTO
{
    public function __construct(
        public int $partnerId = 0,
        public string $title = '',
        public ?string $slug = null,
        public ?string $category = null,
        public ?string $content = null,
        public ?string $featuredImage = null,
        public string $status = 'draft',
        public SeoMeta $seo = new SeoMeta(),
        public array $tags = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $tags = [];
        if (array_key_exists('tags', $data)) {
            $raw = $data['tags'];
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $tags = $decoded;
                } else {
                    $tags = array_filter(array_map('trim', explode(',', $raw)));
                }
            } elseif (is_array($raw)) {
                $tags = $raw;
            }
        }
        return new self(
            title: (string)($data['title'] ?? ''),
            slug: $data['slug'] ?? null,
            category: $data['category'] ?? null,
            content: $data['content'] ?? null,
            featuredImage: $data['featured_image'] ?? null,
            status: (string)($data['status'] ?? 'draft'),
            seo: SeoMeta::fromArray($data),
            tags: $tags,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'content' => $this->content,
            'featured_image' => $this->featuredImage,
            'status' => $this->status,
        ] + $this->seo->toArray();
    }
}


