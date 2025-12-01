<x-guest-layout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-extrabold tracking-tight">Contact Us</h1>
            <p class="mt-2 text-white/90">We'd love to hear from you.</p>
        </div>
    </section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in-up">
        <div class="bg-white border rounded-2xl p-6">
            <h2 class="font-semibold mb-3">Send a message</h2>
            @if (session('status') === 'message-sent')
                <div class="mb-4 rounded-md bg-green-50 text-green-700 px-3 py-2 text-sm">
                    Thank you! Your message has been sent.
                </div>
            @endif
            <form class="space-y-4" action="{{ route('contact.store') }}" method="post">
                @csrf
                <input type="hidden" name="submitted_at" value="{{ now()->timestamp }}">
                <div class="hidden" aria-hidden="true">
                    <label>Leave this field empty</label>
                    <input type="text" name="hp" tabindex="-1" autocomplete="off">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input required name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input required name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea required name="message" rows="4" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] text-white font-medium transition-colors">Submit</button>
            </form>
        </div>
        <div class="space-y-4">
            <div class="p-6 bg-white border rounded-2xl">
                <h3 class="font-semibold">Email</h3>
                <p class="text-sm text-gray-700 mt-1"><a href="mailto:{{ $siteSettings->email_address }}" class="text-[color:var(--brand-primary)] hover:underline">{{ $siteSettings->email_address }}</a></p>
            </div>
            <div class="p-6 bg-white border rounded-2xl">
                <h3 class="font-semibold">Office</h3>
                <p class="text-sm text-gray-700 mt-1">{{ $siteSettings->address }}</p>
                <p class="text-sm text-gray-700 mt-1"><a href="tel:{{ $siteSettings->contact_number }}" class="text-[color:var(--brand-primary)] hover:underline">{{ $siteSettings->contact_number }}</a></p>
            </div>
            <div class="bg-white border rounded-2xl overflow-hidden">
                <div class="aspect-video">
                    <iframe
                        src="{{ $siteSettings->map_url }}"
                        class="w-full h-full border-0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


