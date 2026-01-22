<?php

namespace Database\Factories;

use Faker\Factory as Faker;
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
        $faker = Faker::create();
        $title = ucfirst($faker->unique()->sentence(4));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$faker->unique()->numberBetween(100, 999),
            'category' => $faker->randomElement(['news', 'guides', 'updates', 'tips']),
            'content' => '<p>'.implode('</p><p>', $faker->paragraphs(3)).'</p>',
            'featured_image' => null,
            'status' => $faker->randomElement(['draft', 'published']),
            'seo_title' => $title,
            'seo_description' => $faker->sentence(12),
            'seo_keywords' => implode(', ', $faker->words(6)),
            'tags' => [$faker->word(), $faker->word()],
        ];
    }
}
