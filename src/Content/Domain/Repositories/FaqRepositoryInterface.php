<?php
namespace Src\Content\Domain\Repositories;

/**
 * FaqRepositoryInterface interface.
 *
 * @package Src\Content\Domain\Repositories
 */
interface FaqRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20);
    /**
     * Handle List.
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);
}


