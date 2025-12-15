<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\Group;

/**
 * ShowGroupAction application action.
 */
class ShowGroupAction
{
    public function execute(Group $group): Group
    {
        return $group;
    }
}


