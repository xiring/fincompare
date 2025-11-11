<?php
namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Domain\Repositories\FaqRepositoryInterface;
use Src\Content\Domain\Entities\Faq;

/**
 * EloquentFaqRepository repository.
 *
 * @package Src\Content\Infrastructure\Persistence
 */
class EloquentFaqRepository implements FaqRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
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

    /**
     * Handle List.
     *
     * @param array $filters
     * @return mixed
     */
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


