<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;

class ListBlogPostsAction
{
    public function execute(array $filters = [], int $perPage = 20)
    {
        $query = BlogPost::query()
            ->when($filters['q'] ?? null, fn($q,$s)=>$q->where('title','like','%'.$s.'%'))
            ->when($filters['status'] ?? null, fn($q,$s)=>$q->where('status',$s))
            ->orderBy('created_at','desc');
        return $query->paginate($perPage);
    }
}


