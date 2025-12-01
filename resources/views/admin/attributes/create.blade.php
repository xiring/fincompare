<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Create Attribute') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Add a new product attribute</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.attributes.store') }}" method="post" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="product_category_id" value="Product Category" />
                    <select id="product_category_id" name="product_category_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ (string)old('product_category_id')===(string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('product_category_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="slug" value="Slug (optional)" />
                    <x-text-input id="slug" name="slug" value="{{ old('slug') }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="data_type" value="Data Type" />
                        <select id="data_type" name="data_type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                            @foreach(['text','number','percentage','boolean','json'] as $dt)
                                <option value="{{ $dt }}" {{ old('data_type')===$dt?'selected':'' }}>{{ ucfirst($dt) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="unit" value="Unit (optional)" />
                        <x-text-input id="unit" name="unit" value="{{ old('unit') }}" class="mt-1 block w-full" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_filterable" name="is_filterable" value="1" {{ old('is_filterable') ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                        <x-input-label for="is_filterable" value="Filterable" class="!mb-0" />
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_required" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                        <x-input-label for="is_required" value="Required" class="!mb-0" />
                    </div>
                </div>

                <div>
                    <x-input-label for="sort_order" value="Sort Order" />
                    <x-text-input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}" class="mt-1 block w-full" />
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.attributes.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Save Attribute</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


