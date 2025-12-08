<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\Faq;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;

/**
 * DeleteFaqAction application action.
 */
class DeleteFaqAction
{
    public function __construct(private FaqRepositoryInterface $repository) {}

    public function execute(Faq $faq): void
    {
        $this->repository->delete($faq);
    }
}
