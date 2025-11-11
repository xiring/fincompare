<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;

/**
 * DeleteBlogPostAction application action.
 */
class DeleteBlogPostAction
{
    public function execute(BlogPost $post): void
    {
        $post->delete();
    }
}
