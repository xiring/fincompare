<?php
namespace Src\Content\Domain\Repositories;

interface FaqRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20);
}


