<?php

namespace Src\Partners\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Partners\Application\DTOs\PartnerDTO;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentPartnerRepository repository.
 */
class EloquentPartnerRepository implements PartnerRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'status', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return Partner::query()
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Partner
    {
        return Partner::find($id);
    }

    public function create(PartnerDTO $dto): Partner
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Partner::create($data);
    }

    public function update(Partner $partner, PartnerDTO $dto): Partner
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $partner->update($data);

        return $partner;
    }

    public function delete(Partner $partner): void
    {
        $partner->delete();
    }
}
