<?php

namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;

/**
 * EloquentBlogPostRepository repository.
 */
class EloquentBlogPostRepository implements BlogPostRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20)
    {
        $query = BlogPost::query()
            ->when($filters['q'] ?? null, fn ($q, $s) => $q->where('title', 'like', '%'.$s.'%'))
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s));

        // Sorting
        $sort = $filters['sort'] ?? 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowed = ['id', 'created_at', 'title', 'status'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'id';
        }
        $query->orderBy($sort, $dir);

        return $query->paginate($perPage);
    }

    public function find(int $id): ?BlogPost
    {
        return BlogPost::find($id);
    }

    public function create(BlogPostDTO $dto): BlogPost
    {
        $data = $dto->toArray();
        $data['tags'] = $dto->tags;
        return BlogPost::create($data);
    }

    public function update(BlogPost $blogPost, BlogPostDTO $dto): BlogPost
    {
        $data = $dto->toArray();
        $data['tags'] = $dto->tags;
        $blogPost->update($data);
        return $blogPost;
    }

    public function delete(BlogPost $blogPost): void
    {
        $blogPost->delete();
    }
}
