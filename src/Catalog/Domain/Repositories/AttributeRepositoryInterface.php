<?php

namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Src\Catalog\Application\DTOs\AttributeDTO;
use Src\Catalog\Domain\Entities\Attribute;

/**
 * AttributeRepositoryInterface interface.
 */
interface AttributeRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function find(int $id): ?Attribute;

    public function create(AttributeDTO $dto): Attribute;

    public function update(Attribute $attribute, AttributeDTO $dto): Attribute;

    public function delete(Attribute $attribute): void;

    public function byCategory(int $productCategoryId): Collection;
}
