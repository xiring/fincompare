<x-guest-layout>
    <div x-data="productDetails({ id: {{ $product->id }}, compareIds: @json(session('compare_ids', [])) })" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center gap-4">
            <img src="{{ $product->partner->logo_url ?? 'https://via.placeholder.com/56' }}" alt="{{ $product->partner->name ?? 'Partner' }}" class="w-14 h-14 rounded bg-gray-100 object-contain">
            <div>
                <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
                <p class="text-sm text-gray-600">{{ $product->partner->name ?? '' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button @click="tab='overview'" :class="tab==='overview' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Overview</button>
                    <button @click="tab='features'" :class="tab==='features' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Features</button>
                    <button @click="tab='eligibility'" :class="tab==='eligibility' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Eligibility</button>
                    <button @click="tab='documents'" :class="tab==='documents' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Documents</button>
                </nav>
            </div>

            <div class="mt-6 prose max-w-none" x-show="tab==='overview'" x-cloak>
                {!! $product->description !!}
            </div>
        </div>

        <div class="mt-8" x-show="tab==='features'" x-cloak>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">Features & Attributes</h2>
                <div class="relative">
                    <input x-model="featureQuery" type="text" placeholder="Search attributes..." class="w-64 rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class="overflow-x-auto bg-white border rounded-lg">
                <table class="min-w-full divide-y">
                    <tbody class="divide-y">
                        @foreach(($attributes ?? []) as $attr)
                            <tr x-show="matchesQuery('{{ Str::of($attr->name)->replace("'","\'") }}','{{ Str::of($attr->value)->replace("'","\'") }}')">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 w-1/3">{{ $attr->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $attr->value ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6" x-show="tab==='eligibility' || tab==='documents'" x-cloak>
            <div x-show="tab==='eligibility'">
                <h3 class="font-semibold mb-2">Eligibility Criteria</h3>
                <div class="prose max-w-none">{!! $product->eligibility ?? '<p>Details provided by the partner.</p>' !!}</div>
            </div>
            <div x-show="tab==='documents'">
                <h3 class="font-semibold mb-2">Required Documents</h3>
                <div class="prose max-w-none">{!! $product->documents ?? '<p>Details provided by the partner.</p>' !!}</div>
            </div>
        </div>

        <div class="mt-10 flex items-center gap-3">
            <button @click="copyLink" type="button" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-100 text-gray-700 font-medium">Copy Link</button>
            <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-amber-500 text-white' : 'bg-white text-gray-700 border'" class="inline-flex items-center px-4 py-2 rounded-md font-medium border">@{{ inCompare ? 'Remove from Compare' : 'Add to Compare' }}</button>
            <a href="{{ route('leads.create', ['product'=>$product->id]) }}" class="inline-flex items-center px-6 py-3 rounded-md bg-indigo-600 text-white font-semibold">Send Inquiry</a>
            <a href="{{ route('compare') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-slate-800 text-white font-medium" x-show="inCompare">Compare Now</a>
        </div>
    </div>
</x-guest-layout>

<script>
    function productDetails(initial) {
        return {
            tab: 'overview',
            featureQuery: '',
            inCompare: (initial.compareIds || []).includes(initial.id),
            matchesQuery(name, value) {
                if (!this.featureQuery) return true;
                const q = this.featureQuery.toLowerCase();
                return (name||'').toLowerCase().includes(q) || (value||'').toLowerCase().includes(q);
            },
            async toggleCompare() {
                try {
                    const res = await fetch('{{ route('compare.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: initial.id, selected: !this.inCompare })
                    });
                    const data = await res.json();
                    if (data.ok) this.inCompare = !this.inCompare;
                } catch (e) {}
            },
            async copyLink() {
                try { await navigator.clipboard.writeText(window.location.href); } catch(e) {}
            }
        }
    }
</script>
