<x-guest-layout>
    <div x-data="compareStore()" @compare-toggle.window="toggle($event.detail)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <div class="bg-white border rounded-2xl p-4 md:p-5">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $category->name ?? 'All Products' }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Browse, filter, and compare side-by-side.</p>
                    </div>
                    <form method="get" class="flex-1 md:max-w-lg">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                            </span>
                            <input name="q" value="{{ request('q') }}" placeholder="Search products" class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            <button class="hidden" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach(($categories ?? collect())->take(8) as $c)
                        <a href="{{ url('/products?category_id='.$c->id) }}" class="px-3 py-1.5 rounded-full text-xs border {{ (string)request('category_id')===(string)$c->id ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-gray-50 text-gray-700' }}">{{ $c->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

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
                <div class="fixed top-0 left-0 right-0 z-30" x-show="showBar" x-cloak>
                    <div class="h-0.5 bg-indigo-600 transition-all" :style="`width: ${progress}%`"></div>
                </div>
                <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('Catalog.Presentation.Views.Public._product_cards_chunk', ['products'=>$products])
                </div>
                <div id="infinite-sentinel" class="mt-8 flex flex-col items-center justify-center text-sm text-gray-500" x-show="hasNext">
                    <div x-show="loading" class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-4 bg-white border rounded-2xl">
                            <div class="h-5 w-40 bg-gray-200 rounded animate-pulse"></div>
                            <div class="mt-4 h-24 bg-gray-100 rounded animate-pulse"></div>
                        </div>
                        <div class="p-4 bg-white border rounded-2xl">
                            <div class="h-5 w-40 bg-gray-200 rounded animate-pulse"></div>
                            <div class="mt-4 h-24 bg-gray-100 rounded animate-pulse"></div>
                        </div>
                        <div class="p-4 bg-white border rounded-2xl hidden lg:block">
                            <div class="h-5 w-40 bg-gray-200 rounded animate-pulse"></div>
                            <div class="mt-4 h-24 bg-gray-100 rounded animate-pulse"></div>
                        </div>
                    </div>
                    <span x-show="loading" class="mt-3">Loading…</span>
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
        /**
         * Handle Compare store.
         * @return mixed
         */
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
        /**
         * Handle Infinite products.
         * @return mixed
         */
        function infiniteProducts(initialNext){
            return {
                next: initialNext,
                loading: false,
                showBar: false,
                progress: 0,
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
                    this.showBar = true; this.progress = 10;
                    try{
                        const res = await fetch(this.next, { headers: { 'Accept':'application/json' } });
                        this.progress = 60;
                        if (!res.ok) { this.next=null; return; }
                        const json = await res.json();
                        document.getElementById('products-grid').insertAdjacentHTML('beforeend', json.html || '');
                        this.next = json.next || null;
                        this.progress = 100;
                    } finally {
                        this.loading = false;
                        setTimeout(()=>{ this.showBar=false; this.progress=0; }, 300);
                    }
                }
            }
        }
    </script>
</x-guest-layout>


