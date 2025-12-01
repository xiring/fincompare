<x-guest-layout>
    <div x-data="compareStore()" @compare-toggle.window="toggle($event.detail)">
        <!-- Header Section -->
        <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <h1 class="text-3xl font-extrabold tracking-tight">{{ $category->name ?? 'All Products' }}</h1>
                <p class="mt-2 text-white/90">Browse, filter, and compare side-by-side.</p>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <div class="bg-white border rounded-2xl p-4 md:p-5">
                    <form method="get" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                            </span>
                            <input name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-300 focus-brand" />
                            <button class="hidden" type="submit">Search</button>
                        </div>
                    </form>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach(($categories ?? collect())->take(8) as $c)
                            @php($isActive = (isset($category->slug) && $category->slug === $c->slug) || request('category') === $c->slug)
                            <a href="{{ route('categories.public.show', $c->slug) }}"
                               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border font-medium transition-colors {{ $isActive ? 'category-pill-active' : 'bg-gray-50 text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                                @if($c->image_url ?? null)
                                    <img src="{{ $c->image_url }}" alt="{{ $c->name }}" class="w-4 h-4 rounded-full object-cover">
                                @endif
                                <span>{{ $c->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-1/4 w-full">
                <div class="lg:sticky lg:top-8 p-4 bg-white border rounded-lg">
                    <h2 class="text-lg font-semibold mb-4">Filter by</h2>
                    <form method="get" class="space-y-3" action="{{ isset($category->slug) ? route('categories.public.show', $category->slug) : route('products.public.index') }}">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]" onchange="if(this.value) { window.location.href = '{{ url('/categories') }}/' + this.value; } else { window.location.href = '{{ route('products.public.index') }}'; }">
                                <option value="">All</option>
                                @foreach(($categories ?? []) as $c)
                                    <option value="{{ $c->slug }}" {{ (isset($category->slug) && $category->slug === $c->slug) || request('category') === $c->slug ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Partner</label>
                            <select name="partner_id" class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]" onchange="this.form.submit()">
                                <option value="">All</option>
                                @foreach(($partners ?? []) as $p)
                                    <option value="{{ $p->id }}" {{ (string)request('partner_id')===(string)$p->id?'selected':'' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                                <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300 text-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">
                                <span>Featured Products Only</span>
                            </label>
                        </div>
                    </form>
                    @foreach(($category_attributes ?? []) as $group)
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $group['label'] }}</label>
                            @if(($group['type'] ?? 'text')==='range')
                                <input type="range" min="{{ $group['min'] ?? 0 }}" max="{{ $group['max'] ?? 100 }}" x-model="filters['{{ $group['key'] }}']" class="w-full accent-[color:var(--brand-primary)]">
                                <div class="text-xs text-gray-500 mt-1">@{{ filters['{{ $group['key'] }}'] }}</div>
                            @elseif(($group['type'] ?? 'text')==='checkbox')
                                <div class="space-y-1">
                                    @foreach(($group['options'] ?? []) as $opt)
                                        <label class="inline-flex items-center gap-2 text-sm">
                                            <input type="checkbox" :value="'{{ $opt['value'] ?? $opt }}'" x-model="filters['{{ $group['key'] }}']" class="rounded border-gray-300 text-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">
                                            <span>{{ $opt['label'] ?? $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <input type="text" x-model="filters['{{ $group['key'] }}']" class="w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">
                            @endif
                        </div>
                    @endforeach
                    <div class="flex gap-2 mt-4">
                        <a href="{{ url('/products') }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors">Reset</a>
                    </div>
                </div>
            </aside>

            <!-- Products -->
            <main class="lg:w-3/4 w-full" x-data="infiniteProducts('{{ ($products ?? null)?->withQueryString()->nextPageUrl() }}')">
                <div class="fixed top-0 left-0 right-0 z-30" x-show="showBar" x-cloak>
                    <div class="h-0.5 bg-[color:var(--brand-primary)] transition-all" :style="`width: ${progress}%`"></div>
                </div>
                @if(($products ?? null)?->count() > 0)
                    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @include('Catalog.Presentation.Views.Public._product_cards_chunk', ['products'=>$products])
                    </div>
                @else
                    <div class="bg-white border rounded-2xl p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">No products found</h3>
                        <p class="mt-2 text-sm text-gray-600">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('products.public.index') }}" class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[color:var(--brand-primary)] text-white font-medium hover:bg-[color:var(--brand-primary-2)] transition-colors">Browse All Products</a>
                    </div>
                @endif
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
        <div x-show="selected.length > 0" x-cloak class="fixed bottom-4 left-0 right-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white border shadow-lg rounded-full px-4 py-3 flex items-center justify-between">
                    <div class="text-sm text-gray-700">Selected: <span class="font-semibold" x-text="selected.length"></span></div>
                    <a :href="compareUrl()" class="btn-brand-primary inline-flex items-center justify-center px-5 py-2 rounded-full font-semibold transition-colors shadow-sm">
                        Compare (<span x-text="selected.length"></span>)
                    </a>
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
                selected: @json(array_map('intval', session('compare_ids', []))),
                filters: {},
                toggle({id, selected}){
                    id = Number(id);
                    const idx = this.selected.indexOf(id);
                    if (selected && idx === -1) {
                        this.selected.push(id);
                    }
                    if (!selected && idx !== -1) {
                        this.selected.splice(idx, 1);
                    }
                    // Persist selection to session
                    try {
                        fetch('{{ route('compare.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ id: id, selected: Boolean(selected) })
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
                        // Update compare store with latest compare IDs from server
                        if (json.compareIds && window.Alpine) {
                            const compareStore = Alpine.$data(document.querySelector('[x-data*="compareStore"]'));
                            if (compareStore) {
                                compareStore.selected = json.compareIds;
                            }
                        }
                    } finally {
                        this.loading = false;
                        setTimeout(()=>{ this.showBar=false; this.progress=0; }, 300);
                    }
                }
            }
        }
    </script>
</x-guest-layout>


