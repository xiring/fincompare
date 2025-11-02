<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Application\DTOs\AttributeDTO;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;

class UpdateAttributeAction
{
    public function __construct(private AttributeRepositoryInterface $repo) {}

    public function execute(Attribute $attribute, AttributeDTO $dto): Attribute
    {
        return $this->repo->update($attribute, $dto);
    }
}


