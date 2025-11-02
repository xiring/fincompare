<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Src\Content\Domain\Entities\CmsPage;

/**
 * @extends Factory<CmsPage>
 */
class CmsPageFactory extends Factory
{
    protected $model = CmsPage::class;

    public function definition(): array
    {
        $title = ucfirst(fake()->unique()->sentence(3));
        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100,999),
            'seo_title' => $title,
            'seo_description' => fake()->sentence(12),
            'seo_keywords' => implode(', ', fake()->words(6)),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(2)).'</p>',
            'status' => fake()->randomElement(['draft','published']),
        ];
    }
}


