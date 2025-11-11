<?php

namespace Src\Shared\Application\Actions;

use Src\Shared\Application\DTOs\ContactMessageDTO;
use Src\Shared\Domain\Entities\ContactMessage;

/**
 * CreateContactMessageAction application action.
 */
class CreateContactMessageAction
{
    public function execute(ContactMessageDTO $dto): ContactMessage
    {
        return ContactMessage::create($dto->toArray());
    }
}
