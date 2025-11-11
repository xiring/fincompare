<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;

/**
 * ShowBlogPostAction application action.
 */
class ShowBlogPostAction
{
    public function execute(BlogPost $post): BlogPost
    {
        return $post;
    }
}
