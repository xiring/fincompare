<x-guest-layout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-700 via-indigo-600 to-indigo-500 text-white animate-fade-in">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-blue-400/20 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 text-center lg:text-left">
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">Find and compare the best financial products</h1>
                    <p class="mt-4 text-white/90 max-w-2xl">Loans, cards, and more — compare side-by-side and apply with confidence.</p>
                    <div class="mt-8 bg-white/10 backdrop-blur rounded-2xl p-3 ring-1 ring-white/20">
                        <form action="{{ url('/products') }}" method="get" class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                                </span>
                                <input name="q" placeholder="Search for credit cards, personal loans, ..." class="w-full pl-10 pr-3 py-3 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none" />
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ url('/products') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow hover:bg-gray-50">Browse All</a>
                                <button class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-amber-400 text-slate-900 font-semibold shadow hover:bg-amber-300" type="submit">Search</button>
                            </div>
                        </form>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="px-3 py-1 rounded-full bg-white/20">0% APR</span>
                            <span class="px-3 py-1 rounded-full bg-white/20">Cashback</span>
                            <span class="px-3 py-1 rounded-full bg-white/20">Travel</span>
                            <span class="px-3 py-1 rounded-full bg-white/20">Personal Loans</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-md">
                        <div class="aspect-[4/3] rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center">
                            <img src="https://via.placeholder.com/640x480?text=Comparison+Preview" alt="Preview" class="rounded-xl shadow-2xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats band -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 0ms" class="p-5 rounded-2xl border bg-white text-center" x-data="counter(250000, 1400, 'kplus')" x-init="init()">
                    <div class="text-2xl font-extrabold text-gray-900"><span x-text="display">0</span></div>
                    <div class="text-xs text-gray-500 mt-1">Comparisons made</div>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 100ms" class="p-5 rounded-2xl border bg-white text-center" x-data="counter(1200, 1200, 'plus')" x-init="init()">
                    <div class="text-2xl font-extrabold text-gray-900"><span x-text="display">0</span></div>
                    <div class="text-xs text-gray-500 mt-1">Products listed</div>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 200ms" class="p-5 rounded-2xl border bg-white text-center" x-data="counter(95, 1000, 'percent')" x-init="init()">
                    <div class="text-2xl font-extrabold text-gray-900"><span x-text="display">0%</span></div>
                    <div class="text-xs text-gray-500 mt-1">User satisfaction</div>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 300ms" class="p-5 rounded-2xl border bg-white text-center" x-data="counter(50, 1000, 'plus')" x-init="init()">
                    <div class="text-2xl font-extrabold text-gray-900"><span x-text="display">0</span></div>
                    <div class="text-xs text-gray-500 mt-1">Trusted partners</div>
                </div>
            </div>
        </div>
        <script>
            function counter(target, duration, mode){
                return {
                    display: '0',
                    started: false,
                    init(){
                        const obs = new IntersectionObserver((entries)=>{
                            entries.forEach(e=>{ if (e.isIntersecting) { this.start(); obs.disconnect(); } });
                        }, { threshold: 0.4 });
                        obs.observe(this.$el);
                    },
                    start(){
                        if (this.started) return; this.started = true;
                        const startTs = performance.now();
                        const startVal = 0;
                        const easeOut = (t)=>1-Math.pow(1-t,3);
                        const step = (now)=>{
                            const p = Math.min((now-startTs)/duration, 1);
                            const val = startVal + (target-startVal)*easeOut(p);
                            this.display = this.format(val, mode);
                            if (p < 1) requestAnimationFrame(step);
                        };
                        requestAnimationFrame(step);
                    },
                    format(v, mode){
                        if (mode === 'percent') return Math.round(v) + '%';
                        if (mode === 'kplus'){
                            if (v >= 100000) return Math.round(v/1000) + 'k+';
                            return Math.round(v).toLocaleString() + '+';
                        }
                        if (mode === 'plus') return Math.round(v).toLocaleString() + '+';
                        return Math.round(v).toLocaleString();
                    }
                }
            }
        </script>
    </section>

    <!-- Categories -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Explore Financial Products</h2>
                <a href="{{ url('/products') }}" class="text-sm text-indigo-700 hover:underline">Browse all</a>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(($categories ?? []) as $category)
                    <a href="{{ $category->url ?? '#' }}" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: {{ $loop->index * 50 }}ms" class="px-3 py-2 rounded-full border bg-white text-sm text-gray-700 hover:bg-gray-50">{{ $category->name ?? 'Category' }}</a>
                @endforeach
                @if(empty($categories))
                    @for($i=0;$i<8;$i++)
                        <span :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: {{ $i * 50 }}ms" class="px-3 py-2 rounded-full border bg-white text-sm text-gray-700">Category</span>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Benefits / Why choose us -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-8">Why choose FinCompare</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 0ms" class="p-6 bg-white border rounded-2xl">
                    <div class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <h3 class="font-semibold">Transparent comparisons</h3>
                    <p class="text-sm text-gray-600 mt-1">Clear specs and side-by-side views so you always know what you get.</p>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 120ms" class="p-6 bg-white border rounded-2xl">
                    <div class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01"/></svg>
                    </div>
                    <h3 class="font-semibold">Guidance, not noise</h3>
                    <p class="text-sm text-gray-600 mt-1">Smart defaults and highlights to help you decide faster.</p>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 240ms" class="p-6 bg-white border rounded-2xl">
                    <div class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/></svg>
                    </div>
                    <h3 class="font-semibold">No hidden costs</h3>
                    <p class="text-sm text-gray-600 mt-1">We surface fees and terms upfront — no surprises.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-6">
                <h2 class="text-2xl font-bold">Editor's Picks</h2>
                <a href="{{ url('/products') }}" class="text-sm text-indigo-700 hover:underline">See all</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(($featuredProducts ?? []) as $product)
                    <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: {{ $loop->index * 100 }}ms">
                        @include('Catalog.Presentation.Views.Public._product_card',[ 'product'=>$product ])
                    </div>
                @endforeach
                @if(empty($featuredProducts))
                    @for($i=0;$i<3;$i++)
                        <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: {{ $i * 100 }}ms" class="p-4 bg-white border rounded-lg shadow-sm">
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
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold mb-6">Our Trusted Partners</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 items-center">
                @foreach(($partners ?? []) as $partner)
                    <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: {{ $loop->index * 80 }}ms" class="h-12 flex items-center justify-center bg-white border rounded">
                        <img src="{{ $partner->logo_url ?? 'https://via.placeholder.com/120x30?text=Logo' }}" alt="{{ $partner->name ?? 'Partner' }}" class="max-h-8">
                    </div>
                @endforeach
                @if(empty($partners))
                    @for($i=0;$i<5;$i++)
                        <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: {{ $i * 80 }}ms" class="h-12 flex items-center justify-center bg-white border rounded"></div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">What our users say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 0ms" class="p-6 bg-white border rounded-2xl">
                    <p class="text-sm text-gray-700">“Found a perfect card in minutes. The comparison made it obvious.”</p>
                    <div class="mt-4 text-sm text-gray-500">— Alex P.</div>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 120ms" class="p-6 bg-white border rounded-2xl">
                    <p class="text-sm text-gray-700">“Loved the clarity. Sent an inquiry and got a reply same day.”</p>
                    <div class="mt-4 text-sm text-gray-500">— Priya S.</div>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 240ms" class="p-6 bg-white border rounded-2xl">
                    <p class="text-sm text-gray-700">“Side-by-side features saved me a ton of time.”</p>
                    <div class="mt-4 text-sm text-gray-500">— Michael R.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">How FinCompare Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 0ms" class="p-6 bg-white border rounded-lg text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">1</div>
                    <h3 class="font-semibold">Browse & Filter</h3>
                    <p class="text-sm text-gray-600 mt-1">Explore categories and filter by what matters to you.</p>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 120ms" class="p-6 bg-white border rounded-lg text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">2</div>
                    <h3 class="font-semibold">Compare</h3>
                    <p class="text-sm text-gray-600 mt-1">Select products and compare them side-by-side.</p>
                </div>
                <div :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-2'" style="animation-delay: 240ms" class="p-6 bg-white border rounded-lg text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">3</div>
                    <h3 class="font-semibold">Apply & Track</h3>
                    <p class="text-sm text-gray-600 mt-1">Send an inquiry or apply — we route you to partners.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Frequently asked questions</h2>
            <div class="bg-white border rounded-2xl divide-y">
                <div x-data="{open:false}" class="p-5">
                    <button @click="open=!open" class="w-full flex items-center justify-between text-left">
                        <span class="font-medium text-gray-900">Does comparing affect my credit score?</span>
                        <svg :class="open?'rotate-180':''" class="h-5 w-5 text-gray-500 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-2 text-sm text-gray-600">No. Viewing and comparing products on FinCompare does not impact your credit score.</div>
                </div>
                <div x-data="{open:false}" class="p-5">
                    <button @click="open=!open" class="w-full flex items-center justify-between text-left">
                        <span class="font-medium text-gray-900">How do you make recommendations?</span>
                        <svg :class="open?'rotate-180':''" class="h-5 w-5 text-gray-500 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-2 text-sm text-gray-600">We use product data and your filters to surface options that align with your needs.</div>
                </div>
                <div x-data="{open:false}" class="p-5">
                    <button @click="open=!open" class="w-full flex items-center justify-between text-left">
                        <span class="font-medium text-gray-900">Can I apply directly through FinCompare?</span>
                        <svg :class="open?'rotate-180':''" class="h-5 w-5 text-gray-500 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-2 text-sm text-gray-600">Yes — use “Send Inquiry” and we route you to the right partner or form.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Band -->
    <section x-data="reveal()" x-init="init()" :class="visible ? 'animate-fade-in-up' : 'opacity-0 translate-y-3'" class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-indigo-700 text-white px-6 py-8 flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-bold">Not sure which product fits?</h3>
                    <p class="text-white/90 mt-1">Tell us your needs — we’ll help you choose.</p>
                </div>
                <a href="{{ url('/products') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow hover:bg-gray-50">Get Started</a>
            </div>
        </div>
    </section>
    <script>
        function reveal(){
            return {
                visible: false,
                init(){
                    const obs = new IntersectionObserver((entries)=>{
                        entries.forEach(e=>{ if (e.isIntersecting) { this.visible = true; obs.disconnect(); } });
                    }, { threshold: 0.18 });
                    obs.observe(this.$el);
                }
            }
        }
    </script>
</x-guest-layout>


