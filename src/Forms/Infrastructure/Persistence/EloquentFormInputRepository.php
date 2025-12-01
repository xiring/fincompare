<?php

namespace Src\Forms\Infrastructure\Persistence;

use Illuminate\Support\Collection;
use Src\Forms\Application\DTOs\FormInputDTO;
use Src\Forms\Domain\Entities\FormInput;
use Src\Forms\Domain\Repositories\FormInputRepositoryInterface;

/**
 * EloquentFormInputRepository repository.
 */
class EloquentFormInputRepository implements FormInputRepositoryInterface
{
    public function findByForm(int $formId): Collection
    {
        return FormInput::where('form_id', $formId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function find(int $id): ?FormInput
    {
        return FormInput::find($id);
    }

    public function create(FormInputDTO $dto): FormInput
    {
        return FormInput::create($dto->toArray());
    }

    public function update(FormInput $formInput, FormInputDTO $dto): FormInput
    {
        $formInput->update($dto->toArray());

        return $formInput;
    }

    public function delete(FormInput $formInput): void
    {
        $formInput->delete();
    }

    public function deleteByForm(int $formId): void
    {
        FormInput::where('form_id', $formId)->delete();
    }
}

