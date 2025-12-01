<?php

namespace Src\Forms\Domain\Repositories;

use Illuminate\Support\Collection;
use Src\Forms\Application\DTOs\FormInputDTO;
use Src\Forms\Domain\Entities\FormInput;

/**
 * FormInputRepositoryInterface interface.
 */
interface FormInputRepositoryInterface
{
    public function findByForm(int $formId): Collection;

    public function find(int $id): ?FormInput;

    public function create(FormInputDTO $dto): FormInput;

    public function update(FormInput $formInput, FormInputDTO $dto): FormInput;

    public function delete(FormInput $formInput): void;

    public function deleteByForm(int $formId): void;
}

