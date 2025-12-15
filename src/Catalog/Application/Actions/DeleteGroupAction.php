<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\Group;
use Src\Catalog\Domain\Repositories\GroupRepositoryInterface;

/**
 * DeleteGroupAction application action.
 */
class DeleteGroupAction
{
    public function __construct(private GroupRepositoryInterface $repo) {}

    public function execute(Group $group): void
    {
        $this->repo->delete($group);
    }
}


