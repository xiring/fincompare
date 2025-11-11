<?php
namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Domain\Repositories\FaqRepositoryInterface;
use Src\Content\Domain\Entities\Faq;

class EloquentFaqRepository implements FaqRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20)
    {
        $query = Faq::query()
            ->when($filters['q'] ?? null, fn($q,$s)=>$q->where('question','like','%'.$s.'%'))
            ;

        // Sorting
        $sort = $filters['sort'] ?? 'created_at';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowed = ['created_at','question','id'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }
        $query->orderBy($sort, $dir);
        return $query->paginate($perPage);
    }

    public function list(array $filters = [])
    {
        $query = Faq::query()
            ->when($filters['q'] ?? null, fn($q,$s)=>$q->where('question','like','%'.$s.'%'));

        $sort = $filters['sort'] ?? 'created_at';
        $dir = strtolower($filters['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
        $allowed = ['created_at','question','id'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }
        return $query->orderBy($sort, $dir)->get(['id','question','answer','created_at']);
    }
}


