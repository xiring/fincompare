<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\FaqDTO;
use Src\Content\Domain\Entities\Faq;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;

/**
 * UpdateFaqAction application action.
 */
class UpdateFaqAction
{
    public function __construct(private FaqRepositoryInterface $repository) {}

    public function execute(Faq $faq, FaqDTO $dto): Faq
    {
        return $this->repository->update($faq, $dto);
    }
}
