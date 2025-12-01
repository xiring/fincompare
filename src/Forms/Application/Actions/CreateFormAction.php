<?php

namespace Src\Forms\Application\Actions;

use Src\Forms\Application\DTOs\FormDTO;
use Src\Forms\Application\DTOs\FormInputDTO;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Repositories\FormInputRepositoryInterface;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;

/**
 * CreateFormAction application action.
 */
class CreateFormAction
{
    public function __construct(
        private FormRepositoryInterface $formRepo,
        private FormInputRepositoryInterface $inputRepo
    ) {}

    public function execute(FormDTO $formDto, array $inputs = []): Form
    {
        $form = $this->formRepo->create($formDto);

        foreach ($inputs as $inputData) {
            $inputDto = FormInputDTO::fromArray(array_merge($inputData, ['form_id' => $form->id]));
            $this->inputRepo->create($inputDto);
        }

        return $form->load('inputs');
    }
}

