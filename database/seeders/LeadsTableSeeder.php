<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Leads\Domain\Entities\Lead;

class LeadsTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProductCategory::all();
        foreach ($categories as $category) {
            $product = Product::where('product_category_id', $category->id)->inRandomOrder()->first();
            for ($i=0; $i<5; $i++) {
                Lead::firstOrCreate(
                    [
                        'email' => "lead{$category->id}_{$i}@example.com",
                        'product_category_id' => $category->id,
                        'product_id' => $product?->id,
                    ],
                    [
                        'full_name' => 'Lead '.$i,
                        'mobile_number' => '+1-555-010'.random_int(0,9),
                        'message' => 'Interested in '.$category->name,
                        'status' => 'new',
                        'source' => 'seeder',
                        'meta' => ['utm' => 'seed'],
                    ]
                );
            }
        }
    }
}


