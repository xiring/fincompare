<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Application\DTOs\ProductCategoryDTO;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;

/**
 * CreateProductCategoryAction application action.
 */
class CreateProductCategoryAction
{
    public function __construct(private ProductCategoryRepositoryInterface $repo) {}

    public function execute(ProductCategoryDTO $dto): ProductCategory
    {
        return $this->repo->create($dto);
    }
}
