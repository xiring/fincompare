<x-guest-layout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-extrabold tracking-tight">Frequently Asked Questions</h1>
            <p class="mt-2 text-white/90">Answers to common questions about FinCompare.</p>
        </div>
    </section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in-up">
        <div class="bg-white border rounded-2xl divide-y">
            @if(isset($faqs) && $faqs->count())
                @foreach($faqs as $faq)
                <div x-data="{open:false}" class="p-5 animate-fade-in-up">
                    <button @click="open=!open" class="w-full flex items-center justify-between text-left">
                        <span class="font-medium text-gray-900">{{ $faq->question }}</span>
                        <svg :class="open?'rotate-180':''" class="h-5 w-5 text-gray-500 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-2 text-sm text-gray-600">{{ $faq->answer }}</div>
                </div>
                @endforeach
            @else
                <div x-data="{open:false}" class="p-5 animate-fade-in-up">
                    <button @click="open=!open" class="w-full flex items-center justify-between text-left">
                        <span class="font-medium text-gray-900">Is FinCompare free to use?</span>
                        <svg :class="open?'rotate-180':''" class="h-5 w-5 text-gray-500 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="mt-2 text-sm text-gray-600">Yes. Comparing products on FinCompare is free.</div>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>


