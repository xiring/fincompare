<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\Faq;

class ShowFaqAction
{
    public function execute(Faq $faq): Faq
    {
        return $faq;
    }
}


