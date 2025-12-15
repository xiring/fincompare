<?php

namespace Src\Forms\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Forms\Application\DTOs\FormDTO;
use Src\Forms\Domain\Entities\Form;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * FormRepositoryInterface interface.
 */
interface FormRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator;

    public function find(int $id): ?Form;

    public function findBySlug(string $slug): ?Form;

    public function create(FormDTO $dto): Form;

    public function update(Form $form, FormDTO $dto): Form;

    public function delete(Form $form): void;
}

