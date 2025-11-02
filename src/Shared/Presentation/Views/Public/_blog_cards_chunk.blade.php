@foreach(($posts ?? []) as $post)
    <article class="bg-white border rounded-2xl p-5 flex flex-col">
        @if($post->featured_image)
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-40 object-cover rounded-lg mb-3">
        @endif
        <div class="text-xs text-gray-500">{{ $post->category ?? 'General' }}</div>
        <h2 class="mt-1 font-semibold text-gray-900">{{ $post->title }}</h2>
        <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 160) }}</p>
        <a href="{{ route('blog.show', $post->slug) }}" class="mt-3 inline-flex text-sm text-indigo-700 hover:underline">Read more</a>
    </article>
@endforeach


