<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Import Products') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.products.import.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="file" value="CSV File" />
                        <input id="file" name="file" type="file" accept=".csv,text/csv" class="mt-1 block w-full border-gray-300 rounded-md" required />
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="delimiter" value="Delimiter" />
                            <select id="delimiter" name="delimiter" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value=",">Comma (,)</option>
                                <option value=";">Semicolon (;)</option>
                                <option value="|">Pipe (|)</option>
                                <option value="\t">Tab (\t)</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="has_header" value="1" checked />
                                <span>First row is header</span>
                            </label>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600">
                        <p class="font-semibold">Required headers:</p>
                        <p><code>name, partner_id, product_category_id</code></p>
                        <p class="mt-2">Optional headers: <code>slug, description, is_featured, status, attributes</code> (attributes as JSON mapping of attribute_id to value).</p>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Queue Import</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


