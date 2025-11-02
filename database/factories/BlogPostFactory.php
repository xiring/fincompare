<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Src\Content\Domain\Entities\BlogPost;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = ucfirst(fake()->unique()->sentence(4));
        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100,999),
            'category' => fake()->randomElement(['news','guides','updates','tips']),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>',
            'featured_image' => null,
            'status' => fake()->randomElement(['draft','published']),
            'seo_title' => $title,
            'seo_description' => fake()->sentence(12),
            'seo_keywords' => implode(', ', fake()->words(6)),
            'tags' => [fake()->word(), fake()->word()],
        ];
    }
}


