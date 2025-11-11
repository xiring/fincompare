<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\Faq;
use Src\Content\Application\DTOs\FaqDTO;

/**
 * UpdateFaqAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class UpdateFaqAction
{
    public function execute(Faq $faq, FaqDTO $dto): Faq
    {
        $faq->fill($dto->toArray());
        $faq->save();
        return $faq;
    }
}


