<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Application\DTOs\GroupDTO;
use Src\Catalog\Domain\Entities\Group;
use Src\Catalog\Domain\Repositories\GroupRepositoryInterface;

/**
 * CreateGroupAction application action.
 */
class CreateGroupAction
{
    public function __construct(private GroupRepositoryInterface $repo) {}

    public function execute(GroupDTO $dto): Group
    {
        return $this->repo->create($dto);
    }
}


