<?php

namespace Src\Forms\Application\DTOs;

/**
 * FormInputDTO data transfer object.
 */
class FormInputDTO
{
    public function __construct(
        public int $form_id,
        public string $label,
        public string $name,
        public string $type, // text, dropdown, checkbox, textarea
        public ?array $options = null,
        public ?string $placeholder = null,
        public ?string $help_text = null,
        public bool $is_required = false,
        public ?string $validation_rules = null,
        public int $sort_order = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            form_id: (int) $data['form_id'],
            label: $data['label'],
            name: $data['name'],
            type: $data['type'],
            options: $data['options'] ?? null,
            placeholder: $data['placeholder'] ?? null,
            help_text: $data['help_text'] ?? null,
            is_required: (bool) ($data['is_required'] ?? false),
            validation_rules: $data['validation_rules'] ?? null,
            sort_order: (int) ($data['sort_order'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'form_id' => $this->form_id,
            'label' => $this->label,
            'name' => $this->name,
            'type' => $this->type,
            'options' => $this->options,
            'placeholder' => $this->placeholder,
            'help_text' => $this->help_text,
            'is_required' => $this->is_required,
            'validation_rules' => $this->validation_rules,
            'sort_order' => $this->sort_order,
        ];
    }
}

