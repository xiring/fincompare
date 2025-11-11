<?php
namespace Src\Shared\Application\Actions;

use Src\Shared\Domain\Entities\ContactMessage;
use Src\Shared\Application\DTOs\ContactMessageDTO;

class CreateContactMessageAction
{
    public function execute(ContactMessageDTO $dto): ContactMessage
    {
        return ContactMessage::create($dto->toArray());
    }
}


