<?php
namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;
use Src\Content\Domain\Entities\CmsPage;

class EloquentCmsPageRepository implements CmsPageRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20)
    {
        $query = CmsPage::query()
            ->when($filters['q'] ?? null, fn($q,$s)=>$q->where('title','like','%'.$s.'%'))
            ->when($filters['status'] ?? null, fn($q,$s)=>$q->where('status',$s))
            ->orderByDesc('created_at');
        return $query->paginate($perPage);
    }
}


