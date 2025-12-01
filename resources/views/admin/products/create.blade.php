<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Create Product') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Add a new product to the catalog</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.products.store') }}" method="post" class="space-y-6">
                @csrf

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
                        <x-input-label for="partner_id" value="Partner" />
                        <select id="partner_id" name="partner_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                            <option value="">-- Select Partner --</option>
                            @foreach($partners as $p)
                                <option value="{{ $p->id }}" {{ (string)old('partner_id') === (string)$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="product_category_id" value="Product Category" />
                        <select id="product_category_id" name="product_category_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (string)old('product_category_id') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_category_id')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                    <x-input-label for="is_featured" value="Featured" class="!mb-0" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        <option value="active" {{ old('status')==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Product Attributes</h3>
                            <p class="text-sm text-gray-600">Add attribute values for this product</p>
                        </div>
                    </div>

                    <div id="attributes-container" class="space-y-4">
                        <p class="text-sm text-gray-500">Select a category to see available attributes</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Save Product</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const categorySelect = document.getElementById('product_category_id');
        const attributesContainer = document.getElementById('attributes-container');
        let loadedAttributes = [];

        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            if (!categoryId) {
                attributesContainer.innerHTML = '<p class="text-sm text-gray-500">Select a category to see available attributes</p>';
                loadedAttributes = [];
                return;
            }

            // Show loading
            attributesContainer.innerHTML = '<p class="text-sm text-gray-500">Loading attributes...</p>';

            // Fetch attributes for this category
            fetch(`{{ route('admin.attributes.by-category', ':id') }}`.replace(':id', categoryId))
                .then(response => response.json())
                .then(attributes => {
                    loadedAttributes = attributes;
                    renderAttributes(attributes);
                })
                .catch(error => {
                    console.error('Error loading attributes:', error);
                    attributesContainer.innerHTML = '<p class="text-sm text-red-500">Error loading attributes</p>';
                });
        });

        function renderAttributes(attributes) {
            if (attributes.length === 0) {
                attributesContainer.innerHTML = '<p class="text-sm text-gray-500">No attributes available for this category</p>';
                return;
            }

            let html = '';
            attributes.forEach(attr => {
                const inputId = `attr_${attr.id}`;
                const inputName = `attributes[${attr.id}]`;
                const oldAttributes = @json(old('attributes', []));
                const oldValue = oldAttributes && oldAttributes[attr.id] ? oldAttributes[attr.id] : '';

                html += `
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" data-attribute-id="${attr.id}">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <label for="${inputId}" class="block text-sm font-medium text-gray-700">
                                    ${attr.name}
                                    ${attr.is_required ? '<span class="text-red-500">*</span>' : ''}
                                    ${attr.unit ? `<span class="text-gray-500 text-xs">(${attr.unit})</span>` : ''}
                                </label>
                                ${getAttributeInput(attr, inputId, inputName, oldValue)}
                            </div>
                        </div>
                    </div>
                `;
            });

            attributesContainer.innerHTML = html;
        }

        function getAttributeInput(attr, inputId, inputName, value) {
            const required = attr.is_required ? 'required' : '';

            switch(attr.data_type) {
                case 'number':
                case 'percentage':
                    return `<input type="number" id="${inputId}" name="${inputName}" value="${value}" step="any" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" ${required} />`;

                case 'boolean':
                    const checked = value === '1' || value === 'true' || value === true ? 'checked' : '';
                    return `
                        <div class="mt-2">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" id="${inputId}" name="${inputName}" value="1" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" ${checked} />
                                <span class="text-sm text-gray-700">Yes</span>
                            </label>
                        </div>
                    `;

                case 'json':
                    return `<textarea id="${inputId}" name="${inputName}" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm font-mono" ${required} placeholder='{"key": "value"}'></textarea>`;

                default: // string
                    return `<input type="text" id="${inputId}" name="${inputName}" value="${value}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" ${required} />`;
            }
        }

        // Load attributes if category is pre-selected (from old input)
        @if(old('product_category_id'))
            categorySelect.dispatchEvent(new Event('change'));
        @endif
    </script>
</x-app-layout>


