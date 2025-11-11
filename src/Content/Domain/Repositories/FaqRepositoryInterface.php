<?php

namespace Src\Content\Domain\Repositories;

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
}
