<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $form->name }}</h2>
                <p class="mt-1 text-sm text-gray-600">View form details</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.forms.edit', $form) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors">
                    Edit Form
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Name</div>
                    <div class="text-sm font-medium text-gray-900">{{ $form->name }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Slug</div>
                    <div class="text-sm text-gray-900">{{ $form->slug }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status</div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($form->status) }}
                    </span>
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Type</div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->type === 'pre_form' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ $form->type === 'pre_form' ? 'Pre Form' : 'Post Form' }}
                    </span>
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Created</div>
                    <div class="text-sm text-gray-900">{{ $form->created_at->format('F j, Y g:i A') }}</div>
                </div>
                @if($form->description)
                <div class="md:col-span-2">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Description</div>
                    <div class="text-sm text-gray-900">{{ $form->description }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Inputs ({{ $form->inputs->count() }})</h3>
            @if($form->inputs->count() > 0)
                <div class="space-y-4">
                    @foreach($form->inputs->sortBy('sort_order') as $input)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $input->label }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                            {{ ucfirst($input->type) }}
                                        </span>
                                        @if($input->is_required)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Required
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <div><strong>Field Name:</strong> <code class="bg-gray-200 px-1 rounded">{{ $input->name }}</code></div>
                                        @if($input->placeholder)
                                            <div><strong>Placeholder:</strong> {{ $input->placeholder }}</div>
                                        @endif
                                        @if($input->help_text)
                                            <div><strong>Help Text:</strong> {{ $input->help_text }}</div>
                                        @endif
                                        @if($input->type === 'dropdown' && $input->options)
                                            <div><strong>Options:</strong> {{ implode(', ', $input->options) }}</div>
                                        @endif
                                        @if($input->validation_rules)
                                            <div><strong>Validation:</strong> <code class="bg-gray-200 px-1 rounded">{{ $input->validation_rules }}</code></div>
                                        @endif
                                        <div><strong>Sort Order:</strong> {{ $input->sort_order }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No inputs defined for this form.</p>
            @endif
        </div>
    </div>
</x-app-layout>

