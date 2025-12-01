@php($p = $product ?? null)
@php($compareIds = array_map('intval', session('compare_ids', [])))
<div x-data="{
    selected: {{ in_array((int)($p->id ?? 0), $compareIds, true) ? 'true' : 'false' }}
}" class="group bg-white border rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
    @if($p->image ?? null)
        <div class="relative h-48 overflow-hidden bg-gray-100">
            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name ?? 'Product' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </div>
    @endif
    <div class="px-4 pt-4">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="{{ $p->partner->logo_url ?? 'https://placehold.co/48x48' }}" alt="{{ $p->partner->name ?? 'Partner' }}" class="w-12 h-12 rounded bg-gray-100 object-contain">
                <div>
                    <a href="{{ route('products.public.show', $p) }}" class="block hover:underline">
                        <h3 class="font-semibold text-gray-900">{{ $p->name ?? 'Product' }}</h3>
                    </a>
                    <p class="text-xs text-gray-500">{{ $p->partner->name ?? 'Partner' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(($p->is_featured ?? false))
                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-amber-100 text-amber-700 font-semibold">Featured</span>
                @endif
                <button type="button" title="Add to compare" @click="selected=!selected; $dispatch('compare-toggle',{id:{{ $p->id ?? 0 }}, selected})" :class="selected ? 'text-amber-600' : 'text-gray-400 hover:text-gray-600'" class="p-2 rounded-full bg-gray-50">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3h2v14H3V3zm12 0h2v14h-2V3zM8 7h2v10H8V7zm4 0h2v10h-2V7z"/></svg>
                </button>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div class="p-3 rounded-lg bg-gray-50">
                <div class="text-xs text-gray-500">Interest Rate</div>
                <div class="font-medium text-gray-900">{{ $p->attribute_highlights['interest_rate'] ?? '—' }}</div>
            </div>
            <div class="p-3 rounded-lg bg-gray-50">
                <div class="text-xs text-gray-500">Max Amount</div>
                <div class="font-medium text-gray-900">{{ $p->attribute_highlights['max_amount'] ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div class="mt-4 px-4 pb-4 flex items-center justify-between">
        <a href="{{ route('products.public.show',$p) }}" class="inline-flex items-center gap-1 text-[color:var(--brand-primary)] hover:underline text-sm">
            More Details
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
        <div class="flex gap-2">
            <a href="{{ route('leads.create',['product'=>$p->id]) }}" class="px-3 py-2 rounded-lg border bg-[color:var(--brand-primary)]/10 text-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition text-sm">Apply</a>
            <button type="button" @click="selected=!selected; $dispatch('compare-toggle',{id:{{ $p->id ?? 0 }}, selected})" :class="selected ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700'" class="px-3 py-2 rounded-lg text-sm">
                <span x-text="selected ? 'In Compare' : 'Compare'"></span>
            </button>
        </div>
    </div>
</div>


