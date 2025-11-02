<x-guest-layout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Compare financial products and pick the best for you</h1>
            <p class="mt-4 text-white/90 max-w-2xl mx-auto">Transparent comparisons for loans, cards, and more — tailored to your needs.</p>
            <div class="mt-8">
                <a href="{{ url('/products') }}" class="inline-flex items-center rounded-md bg-white px-6 py-3 text-indigo-700 font-semibold shadow hover:bg-gray-50">Start Comparing</a>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold mb-6">Explore Financial Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach(($categories ?? []) as $category)
                    <a href="{{ $category->url ?? '#' }}" class="group p-4 rounded-lg border hover:shadow transition bg-white">
                        <div class="flex items-center justify-center h-12 w-12 rounded bg-indigo-50 text-indigo-600 mb-3">
                            <!-- Placeholder icon -->
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800">{{ $category->name ?? 'Category' }}</div>
                    </a>
                @endforeach
                @if(empty($categories))
                    @for($i=0;$i<6;$i++)
                        <div class="p-4 rounded-lg border bg-white">
                            <div class="flex items-center justify-center h-12 w-12 rounded bg-indigo-50 text-indigo-600 mb-3">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                            </div>
                            <div class="text-sm font-medium text-gray-800">Category</div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold mb-6">Editor's Picks</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(($featuredProducts ?? []) as $product)
                    @include('Catalog.Presentation.Views.Public._product_card',[ 'product'=>$product ])
                @endforeach
                @if(empty($featuredProducts))
                    @for($i=0;$i<3;$i++)
                        <div class="p-4 bg-white border rounded-lg shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded bg-gray-100"></div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Sample Product</h3>
                                    <p class="text-sm text-gray-500">Partner Name</p>
                                </div>
                            </div>
                            <ul class="mt-4 text-sm text-gray-700 space-y-1">
                                <li>Interest Rate: 10.5%</li>
                                <li>Max Amount: $20,000</li>
                            </ul>
                            <div class="mt-4 flex gap-2">
                                <a href="#" class="text-indigo-700 hover:underline">More Details</a>
                                <a href="#" class="px-3 py-1 rounded-md border bg-white text-gray-700 hover:bg-gray-50">Apply</a>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Partners -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold mb-6">Our Trusted Partners</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 items-center">
                @foreach(($partners ?? []) as $partner)
                    <div class="h-12 flex items-center justify-center bg-white border rounded">
                        <img src="{{ $partner->logo_url ?? 'https://via.placeholder.com/120x30?text=Logo' }}" alt="{{ $partner->name ?? 'Partner' }}" class="max-h-8">
                    </div>
                @endforeach
                @if(empty($partners))
                    @for($i=0;$i<5;$i++)
                        <div class="h-12 flex items-center justify-center bg-white border rounded"></div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold mb-6">How FinCompare Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white border rounded-lg text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">1</div>
                    <h3 class="font-semibold">Browse & Filter</h3>
                    <p class="text-sm text-gray-600 mt-1">Explore categories and filter by what matters to you.</p>
                </div>
                <div class="p-6 bg-white border rounded-lg text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">2</div>
                    <h3 class="font-semibold">Compare</h3>
                    <p class="text-sm text-gray-600 mt-1">Select products and compare them side-by-side.</p>
                </div>
                <div class="p-6 bg-white border rounded-lg text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">3</div>
                    <h3 class="font-semibold">Apply & Track</h3>
                    <p class="text-sm text-gray-600 mt-1">Send an inquiry or apply — we route you to partners.</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>


