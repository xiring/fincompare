<?php

namespace Src\Forms\Application\Actions;

use Src\Forms\Application\DTOs\FormDTO;
use Src\Forms\Application\DTOs\FormInputDTO;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Repositories\FormInputRepositoryInterface;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;

/**
 * UpdateFormAction application action.
 */
class UpdateFormAction
{
    public function __construct(
        private FormRepositoryInterface $formRepo,
        private FormInputRepositoryInterface $inputRepo
    ) {}

    public function execute(Form $form, FormDTO $formDto, array $inputs = []): Form
    {
        $this->formRepo->update($form, $formDto);

        // Delete existing inputs and create new ones
        $this->inputRepo->deleteByForm($form->id);

        foreach ($inputs as $inputData) {
            $inputDto = FormInputDTO::fromArray(array_merge($inputData, ['form_id' => $form->id]));
            $this->inputRepo->create($inputDto);
        }

        return $form->load('inputs');
    }
}

