<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Content\Domain\Entities\BlogPost;

class BlogPostsTableSeeder extends Seeder
{
    public function run(): void
    {
        BlogPost::factory()->count(10)->create();
    }
}


