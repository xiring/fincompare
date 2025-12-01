<?php

namespace Src\Forms\Application\Actions;

use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;

/**
 * DeleteFormAction application action.
 */
class DeleteFormAction
{
    public function __construct(private FormRepositoryInterface $repo) {}

    public function execute(Form $form): void
    {
        $this->repo->delete($form);
    }
}

