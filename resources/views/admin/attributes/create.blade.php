<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Attribute') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.attributes.store') }}" method="post" class="max-w-xl space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="product_category_id" value="Product Category" />
                        <select id="product_category_id" name="product_category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="data_type" value="Data Type" />
                            <select id="data_type" name="data_type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                @foreach(['text','number','percentage','boolean','json'] as $dt)
                                    <option value="{{ $dt }}" {{ old('data_type')===$dt?'selected':'' }}>{{ $dt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="unit" value="Unit (optional)" />
                            <x-text-input id="unit" name="unit" value="{{ old('unit') }}" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_filterable" value="1" {{ old('is_filterable') ? 'checked' : '' }} />
                            <label>Filterable</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }} />
                            <label>Required</label>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="sort_order" value="Sort Order" />
                        <x-text-input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}" class="mt-1 block w-full" />
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.attributes.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


