<?php

namespace Src\Content\Infrastructure\Persistence;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;

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
    public function paginate(array $filters = [], int $perPage = 20)
    {
        $query = CmsPage::query()
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
