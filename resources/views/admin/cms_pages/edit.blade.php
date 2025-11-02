<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Page') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.cms-pages.update', $cms_page) }}" method="post" class="max-w-3xl space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="slug" value="Slug (optional)" />
                        <x-text-input id="slug" name="slug" value="{{ old('slug', $cms_page->slug) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" value="{{ old('title', $cms_page->title) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                                @foreach(['draft','published'] as $st)
                                    <option value="{{ $st }}" {{ old('status', $cms_page->status)===$st?'selected':'' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="seo_title" value="SEO Title" />
                            <x-text-input id="seo_title" name="seo_title" value="{{ old('seo_title', $cms_page->seo_title) }}" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="seo_description" value="SEO Description" />
                        <textarea id="seo_description" name="seo_description" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('seo_description', $cms_page->seo_description) }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="seo_keywords" value="SEO Keywords" />
                        <x-text-input id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $cms_page->seo_keywords) }}" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="content" value="Content" />
                        <x-wysiwyg name="content" :value="old('content', $cms_page->content)" />
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.cms-pages.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


