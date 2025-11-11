<?php
namespace Src\Shared\Application\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Src\Shared\Application\DTOs\ContactMessageDTO;

class ContactMessageReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessageDTO $dto)
    {
    }

    public function build(): self
    {
        return $this->subject('New contact message')
            ->replyTo($this->dto->email, $this->dto->name)
            ->view('Shared.Presentation.Views.emails.contact_message', [
                'dto' => $this->dto,
            ]);
    }
}


