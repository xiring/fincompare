<?php

namespace Src\Forms\Application\Actions;

use Src\Forms\Application\DTOs\FormDTO;
use Src\Forms\Application\DTOs\FormInputDTO;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Repositories\FormInputRepositoryInterface;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;

/**
 * DuplicateFormAction application action.
 */
class DuplicateFormAction
{
    public function __construct(
        private FormRepositoryInterface $formRepo,
        private FormInputRepositoryInterface $inputRepo
    ) {}

    public function execute(Form $form): Form
    {
        // Load the form with its inputs
        $form->load('inputs');

        // Create a new form with "Copy of" prefix
        $formDto = new FormDTO(
            name: 'Copy of '.$form->name,
            slug: null, // Will be auto-generated from name
            description: $form->description,
            status: $form->status,
            type: $form->type
        );

        $duplicatedForm = $this->formRepo->create($formDto);

        // Duplicate all inputs
        foreach ($form->inputs as $input) {
            $inputDto = FormInputDTO::fromArray([
                'form_id' => $duplicatedForm->id,
                'label' => $input->label,
                'name' => $input->name,
                'type' => $input->type,
                'options' => $input->options,
                'placeholder' => $input->placeholder,
                'help_text' => $input->help_text,
                'is_required' => $input->is_required,
                'validation_rules' => $input->validation_rules,
                'sort_order' => $input->sort_order,
            ]);
            $this->inputRepo->create($inputDto);
        }

        return $duplicatedForm->load('inputs');
    }
}

