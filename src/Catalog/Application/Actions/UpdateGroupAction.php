<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Application\DTOs\GroupDTO;
use Src\Catalog\Domain\Entities\Group;
use Src\Catalog\Domain\Repositories\GroupRepositoryInterface;

/**
 * UpdateGroupAction application action.
 */
class UpdateGroupAction
{
    public function __construct(private GroupRepositoryInterface $repo) {}

    public function execute(Group $group, GroupDTO $dto): Group
    {
        return $this->repo->update($group, $dto);
    }
}


