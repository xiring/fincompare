<x-guest-layout>
    <!-- Gradient header to match design system -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-extrabold tracking-tight">Compare Products</h1>
            <p class="mt-1 text-white/90">See specs side-by-side. Toggle highlights to spot differences quickly.</p>
        </div>
    </section>

    <div x-data='comparePage({
            products: @json($productsData ?? []),
            features: @json($featuresData ?? []),
            values: @json($values ?? [])
        })' class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(empty($productsData) || count($productsData) === 0)
            <div class="bg-white border rounded-lg p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No products to compare</h3>
                <p class="mt-2 text-sm text-gray-500">Add products to your compare list to see them side by side.</p>
                <div class="mt-6">
                    <a href="{{ route('products.public.index') }}" class="btn-brand-primary inline-flex items-center justify-center px-5 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
                        Browse Products
                    </a>
                </div>
            </div>
        @else
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" x-model="highlightDiff" class="rounded border-gray-300 accent-[color:var(--brand-primary)]">
                        Highlight differences
                    </label>
                    <button @click="clearAll" type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-gray-300 text-sm bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors">Clear all</button>
                </div>
                <a href="{{ route('products.public.index') }}" class="btn-brand-primary inline-flex items-center justify-center text-sm rounded-lg px-4 py-2.5 font-semibold transition-colors shadow-sm">Add more products</a>
            </div>

            <div class="overflow-x-auto bg-white border rounded-lg hidden md:block animate-fade-in-up">
            <table class="min-w-full divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 sticky left-0 bg-gray-50 z-10">Feature</th>
                        <template x-for="p in products" :key="p.id">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <img :src="p.image || p.logo || 'https://placehold.co/28x28'" :alt="p.name" class="w-10 h-10 rounded bg-gray-100 object-cover">
                                            <span x-text="p.name"></span>
                                        </div>
                                        <button @click="removeProduct(p.id)" class="text-xs text-red-600 hover:underline">Remove</button>
                                    </div>
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <template x-for="f in features" :key="f.key">
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 sticky left-0 bg-white z-10" x-text="f.label"></td>
                            <template x-for="p in products" :key="p.id + '-' + f.key">
                                <td class="px-4 py-3 text-sm align-top" :class="cellClass(p.id, f.key)" x-text="(values[p.id] && values[p.id][f.key]) ? values[p.id][f.key] : '—'"></td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Stacked mobile view -->
        <div class="md:hidden space-y-4">
            <template x-for="p in products" :key="p.id">
                <div class="bg-white border rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <img :src="p.image || p.logo || 'https://placehold.co/32x32'" :alt="p.name" class="w-10 h-10 rounded bg-gray-100 object-cover">
                            <div class="font-semibold" x-text="p.name"></div>
                        </div>
                        <button @click="removeProduct(p.id)" class="text-xs text-red-600 hover:underline">Remove</button>
                    </div>
                    <dl class="divide-y">
                        <template x-for="f in features" :key="'m-'+p.id+'-'+f.key">
                            <div class="py-2 grid grid-cols-3 gap-3">
                                <dt class="text-sm text-gray-600 col-span-1" x-text="f.label"></dt>
                                <dd class="text-sm text-gray-900 col-span-2" x-text="(values[p.id] && values[p.id][f.key]) ? values[p.id][f.key] : '—'"></dd>
                            </div>
                        </template>
                    </dl>
                </div>
            </template>
        </div>

            <div class="mt-4 flex justify-end">
                <a href="{{ route('products.public.index') }}" class="inline-flex items-center text-sm text-[color:var(--brand-primary)] hover:underline font-medium">Add more products</a>
            </div>
        @endif
    </div>
</x-guest-layout>

<script>
    /**
     * Handle Compare page.
     * @return mixed
     */
    function comparePage(initial) {
        return {
            products: initial.products || [],
            features: initial.features || [],
            values: initial.values || {},
            highlightDiff: true,
            cellClass(productId, key) {
                if (!this.highlightDiff) return 'text-gray-700';
                const vals = this.products.map(p => (this.values[p.id] || {})[key] ?? null);
                const uniq = Array.from(new Set(vals.map(v => JSON.stringify(v))));
                return uniq.length > 1 ? 'bg-yellow-50 text-gray-900' : 'text-gray-700';
            },
            async removeProduct(id) {
                try {
                    const res = await fetch('{{ route('compare.toggle') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ id, selected: false })
                    });
                    const data = await res.json();
                    if (data.ok) {
                        // Remove from local state
                        this.products = this.products.filter(p => p.id !== id);
                        // If no products left, redirect to show empty state
                        if (this.products.length === 0) {
                            window.location.href = '{{ route('compare') }}';
                        }
                    }
                } catch(e) {
                    console.error('Error removing product:', e);
                }
            },
            async clearAll() {
                if (!confirm('Clear all products from comparison?')) return;

                const toClear = this.products.map(p => p.id);

                // Clear all products from session
                for (const id of toClear) {
                    try {
                        await fetch('{{ route('compare.toggle') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ id, selected: false })
                        });
                    } catch(e) {
                        console.error('Error clearing product:', e);
                    }
                }

                // Redirect to refresh the page and show empty state
                window.location.href = '{{ route('compare') }}';
            }
        }
    }
</script>


