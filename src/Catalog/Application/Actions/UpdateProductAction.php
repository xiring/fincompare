<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;

class UpdateProductAction
{
    public function execute(Product $product, array $data, array $attributesInput): Product
    {
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $data['is_featured'] = (bool)($data['is_featured'] ?? false);

        return DB::transaction(function () use ($product, $data, $attributesInput) {
            $product->update($data);

            $validAttributeIds = Attribute::where('product_category_id', $product->product_category_id)->pluck('id')->all();
            ProductAttributeValue::where('product_id', $product->id)->whereNotIn('attribute_id', $validAttributeIds)->delete();

            $attrs = Attribute::whereIn('id', $validAttributeIds)->get();
            foreach ($attrs as $attr) {
                $raw = $attributesInput[$attr->id] ?? null;
                $payload = ['value_text'=>null,'value_number'=>null,'value_boolean'=>null,'value_json'=>null];

                if ($raw === null || $raw === '') {
                    ProductAttributeValue::where('product_id',$product->id)->where('attribute_id',$attr->id)->delete();
                    continue;
                }
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
