<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\FaqDTO;
use Src\Content\Domain\Entities\Faq;
use Src\Content\Domain\Repositories\FaqRepositoryInterface;

/**
 * CreateFaqAction application action.
 */
class CreateFaqAction
{
    public function __construct(private FaqRepositoryInterface $repository) {}

    public function execute(FaqDTO $dto): Faq
    {
        return $this->repository->create($dto);
    }
}
