<?php

namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentCmsPageRepository repository.
 */
class EloquentCmsPageRepository implements CmsPageRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(ListCriteria $criteria)
    {
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;
        $query = CmsPage::query()
            ->when($criteria->getSearch(), fn ($q, $s) => $q->where('title', 'like', '%'.$s.'%'))
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s));

        // Sorting
        $sort = $criteria->getSort() ?? 'id';
        $dir = $criteria->getDir();
        $allowed = ['id', 'created_at', 'title', 'status'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'id';
        }
        $query->orderBy($sort, $dir);

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?CmsPage
    {
        return CmsPage::find($id);
    }

    public function create(CmsPageDTO $dto): CmsPage
    {
        return CmsPage::create($dto->toArray());
    }

    public function update(CmsPage $cmsPage, CmsPageDTO $dto): CmsPage
    {
        $cmsPage->update($dto->toArray());
        return $cmsPage;
    }

    public function delete(CmsPage $cmsPage): void
    {
        $cmsPage->delete();
    }
}
