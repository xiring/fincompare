@php($p = $product ?? null)
<div x-data="{ selected: false }" class="p-4 bg-white border rounded-lg shadow-sm flex flex-col">
    <a href="{{ route('products.public.show', $p) }}" class="flex items-center gap-3 hover:opacity-90">
        <img src="{{ $p->partner->logo_url ?? 'https://via.placeholder.com/48' }}" alt="{{ $p->partner->name ?? 'Partner' }}" class="w-12 h-12 rounded bg-gray-100 object-contain">
        <div>
            <h3 class="font-semibold text-gray-900">{{ $p->name ?? 'Product' }}</h3>
            <p class="text-sm text-gray-500">{{ $p->partner->name ?? 'Partner' }}</p>
        </div>
    </a>
    <ul class="mt-4 text-sm text-gray-700 space-y-1">
        <li>Interest Rate: {{ $p->attribute_highlights['interest_rate'] ?? '—' }}</li>
        <li>Max Amount: {{ $p->attribute_highlights['max_amount'] ?? '—' }}</li>
    </ul>
    <div class="mt-4 flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('products.public.show',$p) }}" class="text-indigo-700 hover:underline">More Details</a>
            <a href="{{ route('leads.create',['product'=>$p->id]) }}" class="px-3 py-1 rounded-md border bg-white text-gray-700 hover:bg-gray-50">Apply</a>
        </div>
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" x-model="selected" @change="$dispatch('compare-toggle',{id:{{ $p->id ?? 0 }}, selected})" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            <span>Add to Compare</span>
        </label>
    </div>
</div>


