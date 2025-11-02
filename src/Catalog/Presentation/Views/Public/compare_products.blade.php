<x-guest-layout>
    <div x-data="comparePage({
            products: @json(($products ?? [])->map(fn($p)=>['id'=>$p->id,'name'=>$p->name])->values()),
            features: @json(array_values(array_map(fn($f)=>['key'=>$f['key'] ?? $f->key,'label'=>$f['label'] ?? $f->label], $features ?? []))),
            values: @json($values ?? [])
        })" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Compare Products</h1>
            <div class="flex items-center gap-3">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" x-model="highlightDiff" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Highlight differences
                </label>
                <button @click="clearAll" type="button" class="px-3 py-2 rounded-md border text-sm">Clear all</button>
            </div>
        </div>

        <div class="overflow-x-auto bg-white border rounded-lg">
            <table class="min-w-full divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Feature</th>
                        <template x-for="p in products" :key="p.id">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                                <div class="flex items-center justify-between gap-2">
                                    <span x-text="p.name"></span>
                                    <button @click="removeProduct(p.id)" class="text-xs text-red-600 hover:underline">Remove</button>
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <template x-for="f in features" :key="f.key">
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900" x-text="f.label"></td>
                            <template x-for="p in products" :key="p.id + '-' + f.key">
                                <td class="px-4 py-3 text-sm" :class="cellClass(p.id, f.key)" x-text="values[p.id]?.[f.key] ?? '—'"></td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <a href="{{ route('products.public.index') }}" class="text-sm text-indigo-700 hover:underline">Add more products</a>
        </div>
    </div>
</x-guest-layout>

<script>
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
                this.products = this.products.filter(p => p.id !== id);
                try {
                    await fetch('{{ route('compare.toggle') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ id, selected: false })
                    });
                } catch(e) {}
            },
            async clearAll() {
                const toClear = this.products.map(p => p.id);
                this.products = [];
                for (const id of toClear) {
                    try {
                        await fetch('{{ route('compare.toggle') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ id, selected: false })
                        });
                    } catch(e) {}
                }
            }
        }
    }
</script>


