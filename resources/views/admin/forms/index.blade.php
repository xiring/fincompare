<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Forms') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Manage dynamic forms</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <form method="get" class="flex flex-wrap items-center gap-3">
                    <x-text-input name="q" value="{{ request('q') }}" placeholder="Search name" class="min-w-[200px]" />
                    <select name="type" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        <option value="">All types</option>
                        <option value="pre_form" {{ request('type')==='pre_form'?'selected':'' }}>Pre Form</option>
                        <option value="post_form" {{ request('type')==='post_form'?'selected':'' }}>Post Form</option>
                    </select>
                    <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        <option value="">All statuses</option>
                        <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <select name="per_page" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @foreach([10,20,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }} per page</option>
                        @endforeach
                    </select>
                    <x-primary-button>Apply</x-primary-button>
                </form>
                <a href="{{ route('admin.forms.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Form
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-soft border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Inputs</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $form)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $form->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->type === 'pre_form' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $form->type === 'pre_form' ? 'Pre Form' : 'Post Form' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $form->inputs->count() }} input(s)</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($form->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.forms.show', $form) }}" class="text-primary-600 hover:text-primary-900 transition-colors">View</a>
                                    <a href="{{ route('admin.forms.edit', $form) }}" class="text-primary-600 hover:text-primary-900 transition-colors">Edit</a>
                                    <form action="{{ route('admin.forms.duplicate', $form) }}" method="post" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 transition-colors">Duplicate</button>
                                    </form>
                                    <form action="{{ route('admin.forms.destroy', $form) }}" method="post" class="inline" onsubmit="return confirm('Delete this form?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

