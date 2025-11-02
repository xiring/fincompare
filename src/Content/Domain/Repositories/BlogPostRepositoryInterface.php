<?php
namespace Src\Content\Domain\Repositories;

interface BlogPostRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20);
}


