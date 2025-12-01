<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Blog Post') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Update blog post information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.blogs.update', $blog) }}" method="post" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" value="{{ old('title', $blog->title) }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="slug" value="Slug (optional)" />
                    <x-text-input id="slug" name="slug" value="{{ old('slug', $blog->slug) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="category" value="Category" />
                        <x-text-input id="category" name="category" value="{{ old('category', $blog->category) }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            @foreach(['draft','published'] as $st)
                                <option value="{{ $st }}" {{ old('status', $blog->status)===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <x-input-label for="featured_image" value="Featured Image (URL)" />
                    <x-text-input id="featured_image" name="featured_image" type="url" value="{{ old('featured_image', $blog->featured_image) }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="content" value="Content" />
                    <x-wysiwyg name="content" :value="old('content', $blog->content)" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="seo_title" value="SEO Title" />
                        <x-text-input id="seo_title" name="seo_title" value="{{ old('seo_title', $blog->seo_title) }}" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="seo_keywords" value="SEO Keywords" />
                        <x-text-input id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $blog->seo_keywords) }}" class="mt-1 block w-full" />
                    </div>
                </div>

                <div>
                    <x-input-label for="seo_description" value="SEO Description" />
                    <textarea id="seo_description" name="seo_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('seo_description', $blog->seo_description) }}</textarea>
                </div>

                <div>
                    <x-input-label for="tags" value="Tags (comma-separated or JSON array)" />
                    <x-text-input id="tags" name="tags" value='{{ old('tags', is_array($blog->tags) ? implode(", ",$blog->tags) : $blog->tags) }}' class="mt-1 block w-full" />
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Update Post</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


