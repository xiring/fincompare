<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $partners = Partner::all();
        $categories = ProductCategory::all();
        if ($partners->isEmpty() || $categories->isEmpty()) return;

        foreach ($categories as $category) {
            $attrs = Attribute::where('product_category_id', $category->id)->get();
            foreach ($partners as $partner) {
                $name = $partner->name.' '.$category->name;
                $product = Product::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'partner_id' => $partner->id,
                        'product_category_id' => $category->id,
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'description' => 'Auto-generated product for seeding',
                        'is_featured' => (bool)random_int(0,1),
                        'status' => 'active',
                    ]
                );

                foreach ($attrs as $attr) {
                    $payload = ['value_text'=>null,'value_number'=>null,'value_boolean'=>null,'value_json'=>null];
                    switch ($attr->data_type) {
                        case 'number':
                            $payload['value_number'] = random_int(1, 100000);
                            break;
                        case 'percentage':
                            $payload['value_number'] = random_int(1, 300) / 10.0; // e.g. 0.1% - 30%
                            break;
                        case 'boolean':
                            $payload['value_boolean'] = (bool)random_int(0,1);
                            break;
                        case 'json':
                            $payload['value_json'] = ['seeded' => true];
                            break;
                        default:
                            $payload['value_text'] = 'Sample '.$attr->name;
                    }
                    ProductAttributeValue::updateOrCreate(
                        ['product_id'=>$product->id,'attribute_id'=>$attr->id],
                        $payload
                    );
                }
            }
        }
    }
}


