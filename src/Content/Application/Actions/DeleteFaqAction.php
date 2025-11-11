<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\Faq;

/**
 * DeleteFaqAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class DeleteFaqAction
{
    public function execute(Faq $faq): void
    {
        $faq->delete();
    }
}


