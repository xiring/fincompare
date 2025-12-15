<?php

namespace Src\Content\Domain\Repositories;

use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * BlogPostRepositoryInterface interface.
 */
interface BlogPostRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(ListCriteria $criteria);

    public function find(int $id): ?BlogPost;

    public function create(BlogPostDTO $dto): BlogPost;

    public function update(BlogPost $blogPost, BlogPostDTO $dto): BlogPost;

    public function delete(BlogPost $blogPost): void;
}
