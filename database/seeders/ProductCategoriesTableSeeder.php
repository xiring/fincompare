<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\ProductCategory;

class ProductCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name'=>'Personal Loan','description'=>'Unsecured personal loans','is_active'=>true],
            ['name'=>'Credit Card','description'=>'Credit cards with rewards','is_active'=>true],
        ];
        foreach ($categories as $c) {
            ProductCategory::firstOrCreate(
                ['slug' => Str::slug($c['name'])],
                array_merge($c, ['slug' => Str::slug($c['name'])])
            );
        }
    }
}


