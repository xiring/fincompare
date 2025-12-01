<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Product Category') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Update category information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.product-categories.update', $product_category) }}" method="post" enctype="multipart/form-data" class="space-y-6">
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
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('description', $product_category->description) }}</textarea>
                </div>

                <div>
                    <x-input-label for="image" value="Category Image" />
                    @if($product_category->image)
                        <div class="mt-2 mb-3">
                            <img src="{{ asset('storage/' . $product_category->image) }}" alt="{{ $product_category->name }}" class="h-24 w-24 object-cover rounded-lg border border-gray-300">
                        </div>
                    @endif
                    <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                    <p class="mt-1 text-xs text-gray-500">Upload a new image to replace the current one (max 2MB, jpeg, png, jpg, gif, webp)</p>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product_category->is_active) ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                    <x-input-label for="is_active" value="Active" class="!mb-0" />
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Assignment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="pre_form_id" value="Pre Form" />
                            <select id="pre_form_id" name="pre_form_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="">-- No Pre Form --</option>
                                @foreach($preForms as $form)
                                    <option value="{{ $form->id }}" {{ (string)old('pre_form_id', optional($product_category->preForm)->id)===(string)$form->id ? 'selected' : '' }}>{{ $form->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Select a pre-form for this category</p>
                            <x-input-error :messages="$errors->get('pre_form_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="post_form_id" value="Post Form" />
                            <select id="post_form_id" name="post_form_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="">-- No Post Form --</option>
                                @foreach($postForms as $form)
                                    <option value="{{ $form->id }}" {{ (string)old('post_form_id', optional($product_category->postForm)->id)===(string)$form->id ? 'selected' : '' }}>{{ $form->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Select a post-form for this category</p>
                            <x-input-error :messages="$errors->get('post_form_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.product-categories.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Update Category</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


