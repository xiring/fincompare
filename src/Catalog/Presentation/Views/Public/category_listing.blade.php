<x-guest-layout>
    <div x-data="compareStore()" @compare-toggle.window="toggle($event.detail)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-1/4 w-full">
                <div class="p-4 bg-white border rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Filter by</h2>
                    <form method="get" class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                                <option value="">All</option>
                                @foreach(($categories ?? []) as $c)
                                    <option value="{{ $c->id }}" {{ (string)request('category_id')===(string)$c->id?'selected':'' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Partner</label>
                            <select name="partner_id" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                                <option value="">All</option>
                                @foreach(($partners ?? []) as $p)
                                    <option value="{{ $p->id }}" {{ (string)request('partner_id')===(string)$p->id?'selected':'' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    @foreach(($category_attributes ?? []) as $group)
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $group['label'] }}</label>
                            @if(($group['type'] ?? 'text')==='range')
                                <input type="range" min="{{ $group['min'] ?? 0 }}" max="{{ $group['max'] ?? 100 }}" x-model="filters['{{ $group['key'] }}']" class="w-full">
                                <div class="text-xs text-gray-500 mt-1">@{{ filters['{{ $group['key'] }}'] }}</div>
                            @elseif(($group['type'] ?? 'text')==='checkbox')
                                <div class="space-y-1">
                                    @foreach(($group['options'] ?? []) as $opt)
                                        <label class="inline-flex items-center gap-2 text-sm">
                                            <input type="checkbox" :value="'{{ $opt['value'] ?? $opt }}'" x-model="filters['{{ $group['key'] }}']" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            <span>{{ $opt['label'] ?? $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <input type="text" x-model="filters['{{ $group['key'] }}']" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @endif
                        </div>
                    @endforeach
                    <div class="flex gap-2 mt-4">
                        <a href="{{ url('/products') }}" class="px-3 py-2 rounded-md border">Reset</a>
                    </div>
                </div>
            </aside>

            <!-- Products -->
            <main class="lg:w-3/4 w-full" x-data="infiniteProducts('{{ ($products ?? null)?->withQueryString()->nextPageUrl() }}')">
                <h1 class="text-2xl font-bold mb-4">{{ $category->name ?? 'Category' }}</h1>
                <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('Catalog.Presentation.Views.Public._product_cards_chunk', ['products'=>$products])
                </div>
                <div id="infinite-sentinel" class="mt-8 h-8 flex items-center justify-center text-sm text-gray-500" x-show="hasNext">
                    <span x-show="loading">Loading…</span>
                </div>
            </main>
        </div>

        <!-- Compare bar -->
        <div x-show="selected.length>1" x-cloak class="fixed bottom-4 left-0 right-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white border shadow-lg rounded-full px-4 py-3 flex items-center justify-between">
                    <div class="text-sm text-gray-700">Selected: <span class="font-semibold">@{{ selected.length }}</span></div>
                    <a :href="compareUrl()" class="px-5 py-2 rounded-full bg-indigo-600 text-white">Compare (@{{ selected.length }})</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function compareStore(){
            return {
                selected: [],
                filters: {},
                toggle({id, selected}){
                    const idx = this.selected.indexOf(id);
                    if (selected && idx === -1) this.selected.push(id);
                    if (!selected && idx !== -1) this.selected.splice(idx,1);
                    // Persist selection to session
                    try {
                        fetch('{{ route('compare.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams({ id: String(id), selected: String(selected) })
                        });
                    } catch (e) {}
                },
                compareUrl(){
                    const ids = this.selected.join(',');
                    return `/compare?products=${ids}`;
                },
                applyFilters(){ /* placeholder: hook AJAX here */ },
                resetFilters(){ this.filters = {}; },
            }
        }
        function infiniteProducts(initialNext){
            return {
                next: initialNext,
                loading: false,
                get hasNext(){ return !!this.next; },
                init(){
                    const sentinel = document.getElementById('infinite-sentinel');
                    if (!sentinel) return;
                    const observer = new IntersectionObserver((entries)=>{
                        entries.forEach(e=>{ if (e.isIntersecting) this.loadMore(); });
                    });
                    observer.observe(sentinel);
                },
                async loadMore(){
                    if (!this.next || this.loading) return;
                    this.loading = true;
                    try{
                        const res = await fetch(this.next, { headers: { 'Accept':'application/json' } });
                        if (!res.ok) { this.next=null; return; }
                        const json = await res.json();
                        document.getElementById('products-grid').insertAdjacentHTML('beforeend', json.html || '');
                        this.next = json.next || null;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-guest-layout>


