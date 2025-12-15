<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Application\DTOs\ProductCategoryDTO;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;
use Src\Forms\Domain\Entities\Form;

/**
 * UpdateProductCategoryAction application action.
 */
class UpdateProductCategoryAction
{
    public function __construct(private ProductCategoryRepositoryInterface $repo) {}

    public function execute(ProductCategory $category, ProductCategoryDTO $dto): ProductCategory
    {
        return $this->repo->update($category, $dto)->load(['group', 'preForm', 'postForm']);
    }
}
