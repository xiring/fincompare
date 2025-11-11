<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'partner_id' => Partner::factory(),
            'product_category_id' => ProductCategory::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->sentence(),
            'is_featured' => fake()->boolean(),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
