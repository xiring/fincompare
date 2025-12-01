<?php

namespace Src\Forms\Application\Actions;

use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;

/**
 * ShowFormAction application action.
 */
class ShowFormAction
{
    public function __construct(private FormRepositoryInterface $repo) {}

    public function execute(int $id): ?Form
    {
        return $this->repo->find($id);
    }
}

