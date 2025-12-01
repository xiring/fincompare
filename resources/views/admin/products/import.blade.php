<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Import Products') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Import products from CSV file</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.products.import.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="file" value="CSV File" />
                    <input id="file" name="file" type="file" accept=".csv,text/csv" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" required />
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="delimiter" value="Delimiter" />
                        <select id="delimiter" name="delimiter" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            <option value=",">Comma (,)</option>
                            <option value=";">Semicolon (;)</option>
                            <option value="|">Pipe (|)</option>
                            <option value="\t">Tab (\t)</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-3">
                            <input type="checkbox" id="has_header" name="has_header" value="1" checked class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                            <x-input-label for="has_header" value="First row is header" class="!mb-0" />
                        </label>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-2">Required headers:</p>
                    <p class="text-sm text-blue-800 font-mono"><code>name, partner_id, product_category_id</code></p>
                    <p class="text-sm font-semibold text-blue-900 mt-3 mb-2">Optional headers:</p>
                    <p class="text-sm text-blue-800 font-mono"><code>slug, description, is_featured, status, attributes</code></p>
                    <p class="text-xs text-blue-700 mt-2">Note: attributes should be a JSON mapping of attribute_id to value.</p>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Queue Import</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


