<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;

/**
 * DeleteAttributeAction application action.
 */
class DeleteAttributeAction
{
    public function __construct(private AttributeRepositoryInterface $repo) {}

    public function execute(Attribute $attribute): void
    {
        $this->repo->delete($attribute);
    }
}
