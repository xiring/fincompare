<?php
namespace Src\Catalog\Application\Services;

use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;

class ProductAttributeSyncService
{
    public function sync(Product $product, array $attributesInput): void
    {
        $validAttributeIds = Attribute::where('product_category_id', $product->product_category_id)
            ->pluck('id')->all();

        // Remove attributes no longer applicable to this category
        ProductAttributeValue::where('product_id', $product->id)
            ->whereNotIn('attribute_id', $validAttributeIds)
            ->delete();

        $attributes = Attribute::whereIn('id', $validAttributeIds)->get();
        foreach ($attributes as $attribute) {
            $raw = $attributesInput[$attribute->id] ?? null;
            if ($raw === null || $raw === '') {
                ProductAttributeValue::where('product_id', $product->id)
                    ->where('attribute_id', $attribute->id)
                    ->delete();
                continue;
            }

            $payload = $this->mapValuePayload($attribute->data_type, $raw);

            ProductAttributeValue::updateOrCreate(
                ['product_id' => $product->id, 'attribute_id' => $attribute->id],
                $payload
            );
        }
    }

    private function mapValuePayload(string $dataType, mixed $raw): array
    {
        $payload = ['value_text' => null, 'value_number' => null, 'value_boolean' => null, 'value_json' => null];
        switch ($dataType) {
            case 'number':
            case 'percentage':
                $payload['value_number'] = is_numeric($raw) ? (float)$raw : null;
                break;
            case 'boolean':
                $payload['value_boolean'] = (bool)$raw;
                break;
            case 'json':
                $payload['value_json'] = is_string($raw) ? json_decode($raw, true) : $raw;
                break;
            default:
                $payload['value_text'] = (string)$raw;
        }
        return $payload;
    }
}


