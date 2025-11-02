<?php
namespace Src\Catalog\Presentation\Controllers\Public;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->integer('category_id');
        $partnerId = $request->integer('partner_id');

        $products = Product::query()->with(['partner'])
            ->when($request->get('q'), fn($q,$s)=>$q->where('name','like','%'.$s.'%'))
            ->when($categoryId, fn($q,$id)=>$q->where('product_category_id',$id))
            ->when($partnerId, fn($q,$id)=>$q->where('partner_id',$id))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $category = $categoryId ? ProductCategory::find($categoryId) : (object)['name' => 'All Products'];
        $category_attributes = [];
        $categories = ProductCategory::orderBy('name')->get(['id','name']);
        $partners = Partner::orderBy('name')->get(['id','name']);

        if ($request->wantsJson()) {
            $html = view()->file(base_path('src/Catalog/Presentation/Views/Public/_product_cards_chunk.blade.php'), [
                'products' => $products,
            ])->render();
            return response()->json([
                'html' => $html,
                'next' => $products->nextPageUrl(),
            ]);
        }

        return view()->file(base_path('src/Catalog/Presentation/Views/Public/category_listing.blade.php'), compact('products','category','category_attributes','categories','partners'));
    }

    public function show(Product $product)
    {
        $attributes = $product->attributeValues()->with('attribute')->get()->map(function($av){
            return (object)[
                'name' => $av->attribute->name ?? 'Attribute',
                'value' => $av->value_text ?? $av->value_number ?? ($av->value_boolean ? 'Yes' : 'No') ?? ($av->value_json ? json_encode($av->value_json) : null),
            ];
        });

        return view()->file(base_path('src/Catalog/Presentation/Views/Public/product_details.blade.php'), [
            'product' => $product->load('partner'),
            'attributes' => $attributes,
        ]);
    }

    public function compare(Request $request)
    {
        $ids = $request->get('products');
        if (is_string($ids)) $ids = array_filter(array_map('intval', explode(',', $ids)));
        if (empty($ids)) $ids = (array)session('compare_ids', []);

        $products = Product::query()->with('partner')->whereIn('id', $ids)->get();
        $features = collect();
        $values = [];
        foreach ($products as $p) {
            $avs = $p->attributeValues()->with('attribute')->get();
            foreach ($avs as $av) {
                $key = (string)$av->attribute->id;
                $features[$key] = ['key'=>$key,'label'=>$av->attribute->name];
                $values[$p->id][$key] = $av->value_text ?? $av->value_number ?? ($av->value_boolean ? 'Yes' : 'No') ?? ($av->value_json ? json_encode($av->value_json) : null);
            }
        }

        return view()->file(base_path('src/Catalog/Presentation/Views/Public/compare_products.blade.php'), [
            'products'=>$products,
            'features'=>$features->values()->all(),
            'values'=>$values,
        ]);
    }

    public function toggleCompare(Request $request)
    {
        $id = (int)$request->input('id');
        $selected = filter_var($request->input('selected'), FILTER_VALIDATE_BOOLEAN);
        $ids = (array)session('compare_ids', []);
        if ($selected && !in_array($id, $ids)) $ids[] = $id;
        if (!$selected) $ids = array_values(array_diff($ids, [$id]));
        session(['compare_ids'=>$ids]);
        return response()->json(['ok'=>true,'ids'=>$ids]);
    }
}


