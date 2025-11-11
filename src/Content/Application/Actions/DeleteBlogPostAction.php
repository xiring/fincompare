<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;

/**
 * DeleteBlogPostAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class DeleteBlogPostAction
{
    public function execute(BlogPost $post): void
    {
        $post->delete();
    }
}


