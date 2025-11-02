<x-app-layout>
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Edit Attribute</h1>
    <form action="{{ route('admin.attributes.update', $attribute) }}" method="post" class="space-y-3 max-w-xl">
        @csrf
        @method('PATCH')
        <div>
            <label class="block">Name</label>
            <input name="name" value="{{ old('name', $attribute->name) }}" class="border p-2 w-full" required />
            @error('name')<div class="text-red-700">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block">Slug (optional)</label>
            <input name="slug" value="{{ old('slug', $attribute->slug) }}" class="border p-2 w-full" />
            @error('slug')<div class="text-red-700">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block">Data Type</label>
            <select name="data_type" class="border p-2 w-full">
                @foreach(['text','number','percentage','boolean','json'] as $dt)
                    <option value="{{ $dt }}" {{ old('data_type', $attribute->data_type)===$dt?'selected':'' }}>{{ $dt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block">Unit</label>
            <input name="unit" value="{{ old('unit', $attribute->unit) }}" class="border p-2 w-full" />
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_filterable" value="1" {{ old('is_filterable', $attribute->is_filterable) ? 'checked' : '' }} />
            <label>Filterable</label>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_required" value="1" {{ old('is_required', $attribute->is_required) ? 'checked' : '' }} />
            <label>Required</label>
        </div>
        <div>
            <label class="block">Sort Order</label>
            <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $attribute->sort_order) }}" class="border p-2 w-full" />
        </div>
        <input type="hidden" name="product_category_id" value="{{ $attribute->product_category_id }}" />
        <div class="flex gap-2">
            <a href="{{ route('admin.product-categories.edit', $attribute->product_category_id) }}" class="px-3 py-2 bg-gray-500 text-white rounded">Back</a>
            <button class="px-3 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
</x-app-layout>


