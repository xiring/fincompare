<?php
namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;
use Src\Content\Domain\Entities\BlogPost;

class EloquentBlogPostRepository implements BlogPostRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20)
    {
        $query = BlogPost::query()
            ->when($filters['q'] ?? null, fn($q,$s)=>$q->where('title','like','%'.$s.'%'))
            ->when($filters['status'] ?? null, fn($q,$s)=>$q->where('status',$s))
            ->orderByDesc('created_at');
        return $query->paginate($perPage);
    }
}


