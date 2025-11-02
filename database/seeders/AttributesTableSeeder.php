<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\ProductCategory;

class AttributesTableSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Personal Loan' => [
                ['name'=>'Interest Rate','data_type'=>'percentage','unit'=>'%','is_filterable'=>true,'sort_order'=>10],
                ['name'=>'Min Amount','data_type'=>'number','unit'=>null,'is_filterable'=>true,'sort_order'=>20],
                ['name'=>'Max Amount','data_type'=>'number','unit'=>null,'is_filterable'=>true,'sort_order'=>30],
                ['name'=>'Tenure Months','data_type'=>'number','unit'=>'months','is_filterable'=>true,'sort_order'=>40],
                ['name'=>'Preapproved','data_type'=>'boolean','unit'=>null,'is_filterable'=>true,'sort_order'=>50],
            ],
            'Credit Card' => [
                ['name'=>'Annual Fee','data_type'=>'number','unit'=>null,'is_filterable'=>true,'sort_order'=>10],
                ['name'=>'Reward Rate','data_type'=>'percentage','unit'=>'%','is_filterable'=>true,'sort_order'=>20],
                ['name'=>'Provider','data_type'=>'text','unit'=>null,'is_filterable'=>true,'sort_order'=>30],
            ],
        ];

        foreach ($map as $categoryName => $attributes) {
            $category = ProductCategory::where('slug', Str::slug($categoryName))->first();
            if (!$category) continue;

            foreach ($attributes as $idx => $attr) {
                Attribute::firstOrCreate(
                    ['product_category_id'=>$category->id,'slug'=>Str::slug($attr['name'])],
                    [
                        'product_category_id'=>$category->id,
                        'name'=>$attr['name'],
                        'slug'=>Str::slug($attr['name']),
                        'data_type'=>$attr['data_type'],
                        'unit'=>$attr['unit'],
                        'is_filterable'=>$attr['is_filterable'],
                        'is_required'=>false,
                        'sort_order'=>$attr['sort_order'] ?? (($idx+1)*10),
                    ]
                );
            }
        }
    }
}


