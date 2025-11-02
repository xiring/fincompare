<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Product Category') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.product-categories.update', $product_category) }}" method="post" class="max-w-xl space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" value="{{ old('name', $product_category->name) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="slug" value="Slug (optional)" />
                        <x-text-input id="slug" name="slug" value="{{ old('slug', $product_category->slug) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $product_category->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product_category->is_active) ? 'checked' : '' }} />
                        <label>Active</label>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.product-categories.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


