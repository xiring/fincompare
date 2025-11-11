<?php
namespace Src\Content\Application\DTOs;

/**
 * FaqDTO data transfer object.
 *
 * @package Src\Content\Application\DTOs
 */
class FaqDTO
{
    public function __construct(
        public string $question = '',
        public string $answer = '',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            question: (string)($data['question'] ?? ''),
            answer: (string)($data['answer'] ?? ''),
        );
    }

    public function toArray(): array
    {
        return [
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}


