<x-guest-layout>
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-700 via-indigo-600 to-indigo-500 text-white">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-extrabold tracking-tight">Blog</h1>
            <p class="mt-2 text-white/90">Insights and guides from FinCompare.</p>
        </div>
    </section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="infinitePosts('{{ ($posts ?? null)?->nextPageUrl() }}')">
        <div class="fixed top-0 left-0 right-0 z-30" x-show="showBar" x-cloak>
            <div class="h-0.5 bg-indigo-600 transition-all" :style="`width: ${progress}%`"></div>
        </div>
        <form method="get" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="relative md:col-span-2">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </span>
                <input name="q" value="{{ request('q') }}" placeholder="Search posts" class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach(($categories ?? []) as $c)
                        <option value="{{ $c }}" {{ request('category')===$c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tag</label>
                <select name="tag" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach(($tags ?? []) as $t)
                        <option value="{{ $t }}" {{ request('tag')===$t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sort</label>
                <select name="sort" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                    <option value="desc" {{ request('sort','desc')==='desc' ? 'selected' : '' }}>Newest</option>
                    <option value="asc" {{ request('sort')==='asc' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>
        </form>

        <div id="posts-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @include('Shared.Presentation.Views.Public._blog_cards_chunk', ['posts'=>$posts])
        </div>
        <div id="blog-sentinel" class="mt-8 flex flex-col items-center justify-center text-sm text-gray-500" x-show="hasNext">
            <div x-show="loading" class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border rounded-2xl p-5">
                    <div class="h-40 bg-gray-100 rounded animate-pulse"></div>
                    <div class="mt-3 h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="mt-2 h-5 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                    <div class="mt-1 h-4 w-full bg-gray-100 rounded animate-pulse"></div>
                </div>
                <div class="bg-white border rounded-2xl p-5">
                    <div class="h-40 bg-gray-100 rounded animate-pulse"></div>
                    <div class="mt-3 h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="mt-2 h-5 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                    <div class="mt-1 h-4 w-full bg-gray-100 rounded animate-pulse"></div>
                </div>
                <div class="bg-white border rounded-2xl p-5 hidden md:block">
                    <div class="h-40 bg-gray-100 rounded animate-pulse"></div>
                    <div class="mt-3 h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="mt-2 h-5 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                    <div class="mt-1 h-4 w-full bg-gray-100 rounded animate-pulse"></div>
                </div>
            </div>
            <span x-show="loading" class="mt-3">Loading…</span>
        </div>
    </div>
    <script>
        /**
         * Handle Infinite posts.
         * @return mixed
         */
        function infinitePosts(initialNext){
            return {
                next: initialNext,
                loading: false,
                showBar: false,
                progress: 0,
                get hasNext(){ return !!this.next; },
                init(){
                    const sentinel = document.getElementById('blog-sentinel');
                    if (!sentinel) return;
                    const observer = new IntersectionObserver((entries)=>{
                        entries.forEach(e=>{ if (e.isIntersecting) this.loadMore(); });
                    });
                    observer.observe(sentinel);
                },
                async loadMore(){
                    if (!this.next || this.loading) return;
                    this.loading = true; this.showBar = true; this.progress = 10;
                    try{
                        const res = await fetch(this.next, { headers: { 'Accept':'application/json' } });
                        this.progress = 60;
                        if (!res.ok) { this.next=null; return; }
                        const json = await res.json();
                        document.getElementById('posts-grid').insertAdjacentHTML('beforeend', json.html || '');
                        this.next = json.next || null;
                        this.progress = 100;
                    } finally {
                        this.loading = false; setTimeout(()=>{ this.showBar=false; this.progress=0; }, 300);
                    }
                }
            }
        }
    </script>
</x-guest-layout>


