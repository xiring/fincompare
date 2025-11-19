<x-guest-layout>
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <a href="{{ route('blog.index') }}" class="text-white/90 hover:underline text-sm">← Back to Blog</a>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight">{{ $post->title }}</h1>
            <div class="mt-1 text-white/80 text-sm">{{ $post->category ?? 'General' }} · {{ $post->created_at?->format('M j, Y') }}</div>
        </div>
    </section>
    <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in-up">
        @if($post->featured_image)
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-xl mb-6">
        @endif
        <div class="prose max-w-none">
            {!! $post->content !!}
        </div>
    </article>
</x-guest-layout>


