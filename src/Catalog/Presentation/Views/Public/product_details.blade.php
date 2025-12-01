<x-guest-layout>
    <div x-data="productDetails({ id: {{ $product->id }}, compareIds: @json(session('compare_ids', [])) })" class="w-full">
        <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <nav class="text-sm mb-4 inline-flex items-center px-3 py-1.5 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20">
                    <a href="/" class="text-white hover:underline font-medium">Home</a>
                    <span class="mx-2 text-white/80">/</span>
                    <a href="{{ route('products.public.index') }}" class="text-white hover:underline font-medium">Products</a>
                    <span class="mx-2 text-white/80">/</span>
                    <span class="text-white font-semibold">{{ $product->name }}</span>
                </nav>
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 rounded-lg bg-white/10 object-cover ring-2 ring-white/20 shadow-lg">
                    @else
                        @if($product->partner->website_url ?? null)
                            <a href="{{ $product->partner->website_url }}" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity">
                                <img src="{{ $product->partner->logo_url ?? 'https://placehold.co/64x64' }}" alt="{{ $product->partner->name ?? 'Partner' }}" class="w-16 h-16 rounded bg-white/10 object-contain ring-1 ring-white/20">
                            </a>
                        @else
                            <img src="{{ $product->partner->logo_url ?? 'https://placehold.co/64x64' }}" alt="{{ $product->partner->name ?? 'Partner' }}" class="w-16 h-16 rounded bg-white/10 object-contain ring-1 ring-white/20">
                        @endif
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
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 mt-4 sm:mt-0">
                        <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-white/20 text-white border border-white/30 hover:bg-white/30' : 'bg-white border border-white/20 hover:bg-white/90'" class="inline-flex items-center justify-center px-4 py-2.5 sm:py-3 rounded-xl font-semibold transition-colors text-sm sm:text-base" style="color: var(--brand-primary);">
                            <span x-text="inCompare ? 'In Compare' : 'Add to Compare'"></span>
                        </button>
                        <a href="{{ route('leads.create', ['product'=>$product->slug]) }}" class="inline-flex items-center justify-center px-5 py-2.5 sm:py-3 rounded-xl bg-white font-semibold shadow hover:bg-white/90 transition-colors text-sm sm:text-base" style="color: var(--brand-primary);">Send Inquiry</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
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

        <div class="mt-6 animate-fade-in-up">
            <div class="border-b border-gray-200 overflow-x-auto">
                <nav class="-mb-px flex space-x-6 min-w-max" aria-label="Tabs">
                    <button @click="tab='overview'" :class="tab==='overview' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition-colors">Overview</button>
                    <button @click="tab='features'" :class="tab==='features' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition-colors">Features</button>
                    <button @click="tab='eligibility'" :class="tab==='eligibility' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition-colors">Eligibility</button>
                    <button @click="tab='documents'" :class="tab==='documents' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition-colors">Documents</button>
                    <button @click="tab='faq'" :class="tab==='faq' ? 'border-[color:var(--brand-primary)] text-[color:var(--brand-primary)]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-medium transition-colors">FAQ</button>
                </nav>
            </div>

            <div class="mt-6 prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)] prose-a:no-underline hover:prose-a:underline" x-show="tab==='overview'" x-cloak>
                @if($product->description)
                    {!! $product->description !!}
                @else
                    <p class="text-gray-600">No description available for this product.</p>
                @endif
            </div>
        </div>

        <div class="mt-8" x-show="tab==='features'" x-cloak>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-semibold">Features & Attributes</h2>
                <div class="relative">
                    <input x-model="featureQuery" type="text" placeholder="Search attributes..." class="w-full sm:w-64 rounded-lg border-gray-300 text-sm focus-brand pl-9 pr-3 py-2">
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            @if(count($attributes ?? []) > 0)
                <div class="overflow-x-auto bg-white border rounded-lg">
                    <table class="min-w-full divide-y">
                        <tbody class="divide-y">
                            @foreach(($attributes ?? []) as $attr)
                                <tr x-show="matchesQuery('{{ Str::of($attr->name)->replace("'","\'") }}','{{ Str::of($attr->value)->replace("'","\'") }}')" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 w-1/3">{{ $attr->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $attr->value ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white border rounded-lg p-8 text-center">
                    <p class="text-gray-600">No attributes available for this product.</p>
                </div>
            @endif
        </div>

        <div class="mt-8" x-show="tab==='eligibility'" x-cloak>
            <div class="bg-white border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Eligibility Criteria</h3>
                <div class="prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)]">{!! $product->eligibility ?? '<p class="text-gray-600">Details provided by the partner.</p>' !!}</div>
            </div>
        </div>

        <div class="mt-8" x-show="tab==='documents'" x-cloak>
            <div class="bg-white border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Required Documents</h3>
                <div class="prose max-w-none prose-headings:font-semibold prose-a:text-[color:var(--brand-primary)]">{!! $product->documents ?? '<p class="text-gray-600">Details provided by the partner.</p>' !!}</div>
            </div>
        </div>

        <div class="mt-8" x-show="tab==='faq'" x-cloak>
            <div class="bg-white border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Frequently Asked Questions</h3>
                <p class="text-gray-600">No FAQ available for this product.</p>
            </div>
        </div>

        <div class="mt-10 flex flex-wrap items-center gap-3 pb-4">
            <button @click="copyLink($event)" type="button" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Copy Link
            </button>
            <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)]' : 'bg-white border border-[color:var(--brand-primary)] hover:bg-gray-50'" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium border transition-colors" :style="inCompare ? '' : 'color: var(--brand-primary);'">
                <span x-text="inCompare ? 'Remove from Compare' : 'Add to Compare'"></span>
            </button>
            <a href="{{ route('leads.create', ['product'=>$product->slug]) }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-sm btn-brand-primary">Apply Now</a>
            <a href="{{ route('compare') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium transition-colors shadow-sm btn-brand-primary" x-show="inCompare">Compare Now</a>
        </div>

        <div class="fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur-sm border-t shadow-lg" x-show="true">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg bg-gray-100 object-cover">
                    @else
                        @if($product->partner->website_url ?? null)
                            <a href="{{ $product->partner->website_url }}" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity">
                                <img src="{{ $product->partner->logo_url ?? 'https://placehold.co/40x40' }}" alt="{{ $product->partner->name ?? 'Partner' }}" class="w-10 h-10 rounded-lg bg-gray-100 object-contain">
                            </a>
                        @else
                            <img src="{{ $product->partner->logo_url ?? 'https://placehold.co/40x40' }}" alt="{{ $product->partner->name ?? 'Partner' }}" class="w-10 h-10 rounded-lg bg-gray-100 object-contain">
                        @endif
                    @endif
                    <div class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button @click="toggleCompare" type="button" :class="inCompare ? 'bg-[color:var(--brand-primary)] text-white border border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)]' : 'bg-white border border-[color:var(--brand-primary)] hover:bg-gray-50'" class="px-3 py-2 rounded-lg text-sm font-medium border transition-colors whitespace-nowrap" :style="inCompare ? '' : 'color: var(--brand-primary);'">
                        <span x-text="inCompare ? 'Remove from Compare' : 'Add to Compare'"></span>
                    </button>
                    <a href="{{ route('leads.create', ['product'=>$product->slug]) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm whitespace-nowrap btn-brand-primary">Apply Now</a>
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
            async copyLink(e) {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    // Show feedback
                    const btn = e.target.closest('button');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Copied!';
                    btn.classList.add('bg-green-100', 'text-green-700');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('bg-green-100', 'text-green-700');
                    }, 2000);
                } catch(err) {
                    alert('Failed to copy link. Please copy manually: ' + window.location.href);
                }
            }
        }
    }
</script>
