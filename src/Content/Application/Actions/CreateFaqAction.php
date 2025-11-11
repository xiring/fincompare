<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\Faq;
use Src\Content\Application\DTOs\FaqDTO;

class CreateFaqAction
{
    public function execute(FaqDTO $dto): Faq
    {
        return Faq::create($dto->toArray());
    }
}


