<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\FaqDTO;
use Src\Content\Domain\Entities\Faq;

/**
 * CreateFaqAction application action.
 */
class CreateFaqAction
{
    public function execute(FaqDTO $dto): Faq
    {
        return Faq::create($dto->toArray());
    }
}
