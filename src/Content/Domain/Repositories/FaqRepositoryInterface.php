<?php

namespace Src\Content\Domain\Repositories;

use Src\Content\Application\DTOs\FaqDTO;
use Src\Content\Domain\Entities\Faq;

/**
 * FaqRepositoryInterface interface.
 */
interface FaqRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20);

    /**
     * Handle List.
     *
     * @return mixed
     */
    public function list(array $filters = []);

    public function find(int $id): ?Faq;

    public function create(FaqDTO $dto): Faq;

    public function update(Faq $faq, FaqDTO $dto): Faq;

    public function delete(Faq $faq): void;
}
