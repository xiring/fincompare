@foreach(($products ?? []) as $product)
    @include('Catalog.Presentation.Views.Public._product_card',[ 'product'=>$product ])
@endforeach

