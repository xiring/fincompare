<?php

namespace Src\Forms\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Forms\Application\DTOs\FormDTO;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentFormRepository repository.
 */
class EloquentFormRepository implements FormRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'status', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return Form::query()
            ->with(['inputs'])
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->when(($filters['status'] ?? null), fn ($q, $status) => $q->where('status', $status))
            ->when(($filters['type'] ?? null), fn ($q, $type) => $q->where('type', $type))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Form
    {
        return Form::with(['inputs'])->find($id);
    }

    public function findBySlug(string $slug): ?Form
    {
        return Form::with(['inputs'])->where('slug', $slug)->first();
    }

    public function create(FormDTO $dto): Form
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Form::create($data);
    }

    public function update(Form $form, FormDTO $dto): Form
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $form->update($data);

        return $form;
    }

    public function delete(Form $form): void
    {
        $form->delete();
    }
}

