<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Form') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Update form information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.forms.update', $form) }}" method="post" class="space-y-6" id="form-form">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="name" value="Form Name" />
                    <x-text-input id="name" name="name" value="{{ old('name', $form->name) }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="slug" value="Slug (optional)" />
                    <x-text-input id="slug" name="slug" value="{{ old('slug', $form->slug) }}" class="mt-1 block w-full" />
                    <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from name</p>
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('description', $form->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                            <option value="active" {{ old('status', $form->status)==='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $form->status)==='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                            <option value="pre_form" {{ old('type', $form->type ?? 'pre_form')==='pre_form'?'selected':'' }}>Pre Form</option>
                            <option value="post_form" {{ old('type', $form->type ?? 'pre_form')==='post_form'?'selected':'' }}>Post Form</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Form Inputs</h3>
                            <p class="text-sm text-gray-600">Add fields to your form</p>
                        </div>
                        <button type="button" onclick="addInput()" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Input
                        </button>
                    </div>

                    <div id="inputs-container" class="space-y-4">
                        @foreach(old('inputs', $form->inputs) as $index => $input)
                            @php
                                $inputData = is_object($input) ? [
                                    'label' => $input->label,
                                    'name' => $input->name,
                                    'type' => $input->type,
                                    'options' => $input->options,
                                    'options_text' => is_array($input->options) ? implode("\n", $input->options) : '',
                                    'placeholder' => $input->placeholder,
                                    'help_text' => $input->help_text,
                                    'is_required' => $input->is_required,
                                    'validation_rules' => $input->validation_rules,
                                    'sort_order' => $input->sort_order,
                                ] : $input;
                            @endphp
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    addInput(@json($inputData));
                                });
                            </script>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.forms.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Update Form</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let inputIndex = {{ $form->inputs->count() }};

        function addInput(data = {}) {
            const container = document.getElementById('inputs-container');
            const index = inputIndex++;
            const optionsText = data.options_text || (Array.isArray(data.options) ? data.options.join('\n') : '');
            const inputHtml = `
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" data-input-index="${index}">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-gray-900">Input #${index + 1}</h4>
                        <button type="button" onclick="removeInput(${index})" class="text-red-600 hover:text-red-900 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label</label>
                            <input type="text" name="inputs[${index}][label]" value="${data.label || ''}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Field Name</label>
                            <input type="text" name="inputs[${index}][name]" value="${data.name || ''}" pattern="[a-z0-9_]+" placeholder="field_name" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required />
                            <p class="mt-1 text-xs text-gray-500">Lowercase, numbers, and underscores only</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="inputs[${index}][type]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" onchange="toggleOptions(${index})" required>
                                <option value="text" ${data.type === 'text' ? 'selected' : ''}>Text</option>
                                <option value="textarea" ${data.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                                <option value="dropdown" ${data.type === 'dropdown' ? 'selected' : ''}>Dropdown</option>
                                <option value="checkbox" ${data.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" name="inputs[${index}][sort_order]" value="${data.sort_order || index}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
                        </div>
                    </div>
                    <div class="mt-4" id="options-${index}" style="display: ${data.type === 'dropdown' ? 'block' : 'none'};">
                        <label class="block text-sm font-medium text-gray-700">Dropdown Options (one per line)</label>
                        <textarea name="inputs[${index}][options_text]" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Option 1&#10;Option 2&#10;Option 3">${optionsText}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter each option on a new line</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Placeholder</label>
                            <input type="text" name="inputs[${index}][placeholder]" value="${data.placeholder || ''}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Help Text</label>
                            <input type="text" name="inputs[${index}][help_text]" value="${data.help_text || ''}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Validation Rules (optional)</label>
                        <input type="text" name="inputs[${index}][validation_rules]" value="${data.validation_rules || ''}" placeholder="e.g., email|max:255" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
                    </div>
                    <div class="mt-4">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="inputs[${index}][is_required]" value="1" ${data.is_required ? 'checked' : ''} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
                            <span class="text-sm text-gray-700">Required field</span>
                        </label>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', inputHtml);
        }

        function removeInput(index) {
            const input = document.querySelector(`[data-input-index="${index}"]`);
            if (input) {
                input.remove();
            }
        }

        function toggleOptions(index) {
            const select = document.querySelector(`[name="inputs[${index}][type]"]`);
            const optionsDiv = document.getElementById(`options-${index}`);
            if (select && optionsDiv) {
                if (select.value === 'dropdown') {
                    optionsDiv.style.display = 'block';
                } else {
                    optionsDiv.style.display = 'none';
                }
            }
        }

        // Convert options text to JSON array on form submit
        document.getElementById('form-form').addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('[name*="[options_text]"]');
            inputs.forEach(input => {
                const name = input.name.replace('options_text', 'options');
                const text = input.value.trim();
                if (text) {
                    const options = text.split('\n').filter(line => line.trim()).map(line => line.trim());
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = name;
                    hiddenInput.value = JSON.stringify(options);
                    this.appendChild(hiddenInput);
                }
            });
        });
    </script>
</x-app-layout>

