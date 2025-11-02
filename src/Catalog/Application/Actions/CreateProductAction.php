<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;

class CreateProductAction
{
    public function execute(array $data, array $attributesInput): Product
    {
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $data['is_featured'] = (bool)($data['is_featured'] ?? false);

        return DB::transaction(function () use ($data, $attributesInput) {
            $product = Product::create($data);
            $attrs = Attribute::where('product_category_id', $product->product_category_id)->get();
            foreach ($attrs as $attr) {
                $raw = $attributesInput[$attr->id] ?? null;
                if ($raw === null || $raw === '') continue;

                $payload = ['value_text'=>null,'value_number'=>null,'value_boolean'=>null,'value_json'=>null];
                switch ($attr->data_type) {
                    case 'number': case 'percentage': $payload['value_number'] = is_numeric($raw) ? (float)$raw : null; break;
                    case 'boolean': $payload['value_boolean'] = (bool)$raw; break;
                    case 'json': $payload['value_json'] = is_string($raw) ? json_decode($raw, true) : $raw; break;
                    default: $payload['value_text'] = (string)$raw;
                }
                ProductAttributeValue::updateOrCreate(['product_id'=>$product->id,'attribute_id'=>$attr->id], $payload);
            }
            return $product;
        });
    }
}
