<?php

namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Application\DTOs\FaqDTO;
use Src\Content\Domain\Entities\Faq;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;

/**
 * EloquentFaqRepository repository.
 */
class EloquentFaqRepository implements FaqRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20)
    {
        $query = Faq::query()
            ->when($filters['q'] ?? null, fn ($q, $s) => $q->where('question', 'like', '%'.$s.'%'));

        // Sorting
        $sort = $filters['sort'] ?? 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowed = ['id', 'created_at', 'question'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'id';
        }
        $query->orderBy($sort, $dir);

        return $query->paginate($perPage);
    }

    /**
     * Handle List.
     *
     * @return mixed
     */
    public function list(array $filters = [])
    {
        $query = Faq::query()
            ->when($filters['q'] ?? null, fn ($q, $s) => $q->where('question', 'like', '%'.$s.'%'));

        $sort = $filters['sort'] ?? 'created_at';
        $dir = strtolower($filters['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
        $allowed = ['created_at', 'question', 'id'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        return $query->orderBy($sort, $dir)->get(['id', 'question', 'answer', 'created_at']);
    }

    public function find(int $id): ?Faq
    {
        return Faq::find($id);
    }

    public function create(FaqDTO $dto): Faq
    {
        return Faq::create($dto->toArray());
    }

    public function update(Faq $faq, FaqDTO $dto): Faq
    {
        $faq->update($dto->toArray());
        return $faq;
    }

    public function delete(Faq $faq): void
    {
        $faq->delete();
    }
}
