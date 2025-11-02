<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Product') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.products.update', $product) }}" method="post" class="max-w-2xl space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="slug" value="Slug (optional)" />
                        <x-text-input id="slug" name="slug" value="{{ old('slug', $product->slug) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="partner_id" value="Partner" />
                            <select id="partner_id" name="partner_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">-- Select Partner --</option>
                                @foreach($partners as $p)
                                    <option value="{{ $p->id }}" {{ (string)old('partner_id', $product->partner_id) === (string)$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="product_category_id" value="Product Category" />
                            <select id="product_category_id" name="product_category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ (string)old('product_category_id', $product->product_category_id) === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_category_id')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} />
                        <label>Featured</label>
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="active" {{ old('status', $product->status)==='active'?'selected':'' }}>active</option>
                            <option value="inactive" {{ old('status', $product->status)==='inactive'?'selected':'' }}>inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="attributes" value="Attributes JSON (optional)" />
                        <textarea id="attributes" name="attributes" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="{ &quot;ATTR_ID&quot;: &quot;value&quot; }">{{ old('attributes') }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


