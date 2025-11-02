<?php
namespace Src\Catalog\Infrastructure\Persistence;

use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Repositories\ProductFilters;
use Src\Catalog\Domain\Repositories\ProductRepositoryInterface;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function filtered(ProductFilters $filters)
    {
        $query = Product::with(['partner'])
            ->where('product_category_id', $filters->categoryId)
            ->where('status', 'active');

        $filterable = Attribute::where('product_category_id', $filters->categoryId)
            ->where('is_filterable', true)->get();

        foreach ($filterable as $attr) {
            $slug = $attr->slug;
            $eq = $filters->filters[$slug] ?? null;
            $min = $filters->filters[$slug.'_min'] ?? null;
            $max = $filters->filters[$slug.'_max'] ?? null;

            if ($eq !== null && $eq !== '') {
                $query->whereHas('attributeValues', function ($q) use ($attr, $eq) {
                    $q->where('attribute_id', $attr->id)
                      ->when(in_array($attr->data_type, ['number','percentage']), fn($qq)=>$qq->where('value_number', (float)$eq))
                      ->when($attr->data_type === 'boolean', fn($qq)=>$qq->where('value_boolean', (bool)$eq))
                      ->when(!in_array($attr->data_type, ['number','percentage','boolean']), fn($qq)=>$qq->where('value_text', 'like', '%'.$eq.'%'));
                });
            }
            if ($min !== null && $min !== '' && in_array($attr->data_type, ['number','percentage'])) {
                $query->whereHas('attributeValues', fn($q)=>$q->where('attribute_id', $attr->id)->where('value_number', '>=', (float)$min));
            }
            if ($max !== null && $max !== '' && in_array($attr->data_type, ['number','percentage'])) {
                $query->whereHas('attributeValues', fn($q)=>$q->where('attribute_id', $attr->id)->where('value_number', '<=', (float)$max));
            }
        }

        return $query->paginate($filters->perPage);
    }
}
