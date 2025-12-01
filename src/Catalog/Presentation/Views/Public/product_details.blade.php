<x-guest-layout>
    <div x-data="productDetails({ id: {{ $product->id }}, compareIds: @json(session('compare_ids', [])) })" class="w-full">
        <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
            </div>
            <div class="relative px-4 sm:px-6 lg:px-8 py-10">
                <nav class="text-sm text-white/80 mb-4">
                    <a href="/" class="hover:underline">Home</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('products.public.index') }}" class="hover:underline">Products</a>
                    <span class="mx-1">/</span>
                    <span class="text-white">{{ $product->name }}</span>
                </nav>
                <div class="flex items-start gap-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 rounded-lg bg-white/10 object-cover ring-2 ring-white/20 shadow-lg">
                    @else
                        <img src="{{ $product->partner->logo_url ?? 'https://placehold.co/64x64' }}" alt="{{ $product->partner->name ?? 'Partner' }}" class="w-16 h-16 rounded bg-white/10 object-contain ring-1 ring-white/20">
                    @endif
                    <div class="flex-1">
                        <h1 class="text-3xl font-extrabold tracking-tight">{{ $product->name }}</h1>
                        <div class="mt-1 flex items-center gap-3 text-sm text-white/90">
                            <span>{{ $product->partner->name ?? 'Partner' }}</span>
                            @if(($product->is_featured ?? false))
                                <span class="px-2 py-0.5 text-xs rounded-full bg-amber-300 text-slate-900 font-semibold">Featured</span>
                            @endif
                            <span class="px-2 py-0.5 text-xs rounded-full bg-white/20">{{ ucfirst($product->status ?? 'active') }}</span>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="{{ route('leads.create', ['product'=>$product->id]) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[color:var(--brand-primary)] text-white font-semibold shadow hover:bg-[color:var(--brand-primary-2)] transition-colors">Send Inquiry</a>
                        <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-white/20 text-white hover:bg-white/30' : 'bg-white text-[color:var(--brand-primary)] hover:bg-gray-50'" class="inline-flex items-center justify-center px-4 py-3 rounded-xl font-semibold transition-colors">
                            <span x-text="inCompare ? 'In Compare' : 'Add to Compare'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <div class="px-4 sm:px-6 lg:px-8 py-8 pb-24">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 animate-fade-in-up">
                <div class="p-4 rounded-2xl bg-white border">
                    <div class="text-xs text-gray-500">Interest Rate</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $product->attribute_highlights['interest_rate'] ?? '—' }}</div>
                </div>
                <div class="p-4 rounded-2xl bg-white border">
                    <div class="text-xs text-gray-500">Max Amount</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $product->attribute_highlights['max_amount'] ?? '—' }}</div>
                </div>
                <div class="p-4 rounded-2xl bg-white border">
                    <div class="text-xs text-gray-500">Partner</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $product->partner->name ?? '—' }}</div>
                </div>
            </div>

        <div class="mt-6 px-4 sm:px-6 lg:px-8 animate-fade-in-up">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button @click="tab='overview'" :class="tab==='overview' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Overview</button>
                    <button @click="tab='features'" :class="tab==='features' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Features</button>
                    <button @click="tab='eligibility'" :class="tab==='eligibility' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Eligibility</button>
                    <button @click="tab='documents'" :class="tab==='documents' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">Documents</button>
                    <button @click="tab='faq'" :class="tab==='faq' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-1 pb-2 text-sm font-medium">FAQ</button>
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
                    <input x-model="featureQuery" type="text" placeholder="Search attributes..." class="w-64 rounded-md border-gray-300 text-sm focus-brand">
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

        <div class="mt-10 px-4 sm:px-6 lg:px-8 flex items-center gap-3">
            <button @click="copyLink" type="button" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">Copy Link</button>
            <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)]' : 'bg-white text-[color:var(--brand-primary)] border border-[color:var(--brand-primary)]'" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium border hover:opacity-90 transition-opacity">
                <span x-text="inCompare ? 'Remove from Compare' : 'Add to Compare'"></span>
            </button>
            <a href="{{ route('leads.create', ['product'=>$product->id]) }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-[color:var(--brand-primary)] text-white font-semibold hover:bg-[color:var(--brand-primary-2)] transition-colors">Send Inquiry</a>
            <a href="{{ route('compare') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-[color:var(--brand-primary)] text-white font-medium hover:bg-[color:var(--brand-primary-2)] transition-colors" x-show="inCompare">Compare Now</a>
        </div>

        <div class="fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur-sm border-t shadow-lg" x-cloak>
            <div class="px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ $product->partner->logo_url ?? 'https://placehold.co/40x40' }}" class="w-8 h-8 rounded bg-gray-100 object-contain">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)]' : 'bg-white text-[color:var(--brand-primary)] border border-[color:var(--brand-primary)] hover:bg-gray-50'" class="px-3 py-2 rounded-lg text-sm font-medium border transition-colors">
                        <span x-text="inCompare ? 'Remove from Compare' : 'Add to Compare'"></span>
                    </button>
                    <a href="{{ route('leads.create', ['product'=>$product->id]) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] text-white text-sm font-semibold transition-colors">Apply Now</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
    /**
     * Handle Product details.
     * @return mixed
     */
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
