<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;

class ShowBlogPostAction
{
    public function execute(BlogPost $post): BlogPost
    {
        return $post;
    }
}


