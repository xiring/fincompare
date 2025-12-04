<?php

namespace Src\Content\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Src\Content\Application\DTOs\BlogListFiltersDTO;
use Src\Content\Domain\Entities\BlogPost;

/**
 * PublicBlogService - Service for public-facing blog operations.
 */
class PublicBlogService
{
    private const DEFAULT_PER_PAGE = 9;

    /**
     * Get paginated blog posts with filters.
     */
    public function getPaginatedPosts(BlogListFiltersDTO $filters): LengthAwarePaginator
    {
        $query = BlogPost::query()
            ->where('status', 'published');

        // Apply search query
        if ($filters->searchQuery) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters->searchQuery . '%')
                    ->orWhere('content', 'like', '%' . $filters->searchQuery . '%');
            });
        }

        // Apply category filter
        if ($filters->category) {
            $query->where('category', $filters->category);
        }

        // Apply tag filter
        if ($filters->tag) {
            $query->whereJsonContains('tags', $filters->tag);
        }

        return $query
            ->orderBy('created_at', $filters->sort)
            ->paginate($filters->perPage)
            ->withQueryString();
    }

    /**
     * Get available categories from published posts.
     */
    public function getAvailableCategories(): Collection
    {
        return BlogPost::query()
            ->where('status', 'published')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /**
     * Get available tags from published posts.
     */
    public function getAvailableTags(): Collection
    {
        return BlogPost::query()
            ->where('status', 'published')
            ->pluck('tags')
            ->filter()
            ->flatMap(function ($item) {
                return is_array($item) ? $item : [];
            })
            ->unique()
            ->sort()
            ->values();
    }
}

